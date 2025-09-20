<?php
// app/Models/AuditLog.php

namespace App\Models;

use PDO;

class AuditLog {
    private $conn;
    private $table = 'audit_logs';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create audit log entry
    public function create($user_id, $action, $context = '') {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (user_id, action, context, created_at) 
                      VALUES (:user_id, :action, :context, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':context', $context);
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log("Error creating audit log: " . $e->getMessage());
            return false;
        }
    }

    // Alias for create method (for backward compatibility)
    public function log($user_id, $action, $context = '') {
        return $this->create($user_id, $action, $context);
    }

    // Get audit logs for user
    public function getByUserId($user_id, $filters = []) {
        try {
            $query = "SELECT al.*, u.name as user_name 
                      FROM " . $this->table . " al
                      LEFT JOIN users u ON al.user_id = u.id
                      WHERE al.user_id = :user_id";

            $params = [':user_id' => $user_id];

            if (!empty($filters['action'])) {
                $query .= " AND al.action LIKE :action";
                $params[':action'] = '%' . $filters['action'] . '%';
            }
            if (!empty($filters['start_date'])) {
                $query .= " AND al.created_at >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            if (!empty($filters['end_date'])) {
                $query .= " AND al.created_at <= :end_date";
                $params[':end_date'] = $filters['end_date'] . ' 23:59:59';
            }

            $query .= " ORDER BY al.created_at DESC";

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            error_log("Error getting audit logs: " . $e->getMessage());
            return [];
        }
    }

    // Get recent activities for dashboard
    public function getRecentActivities($user_id, $limit = 10) {
        try {
            $query = "SELECT al.*, u.name as user_name 
                      FROM " . $this->table . " al
                      LEFT JOIN users u ON al.user_id = u.id
                      WHERE al.user_id = :user_id
                      ORDER BY al.created_at DESC
                      LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            error_log("Error getting recent activities: " . $e->getMessage());
            return [];
        }
    }

    // Export to Excel
    public function exportToExcel($user_id, $filters = []) {
        try {
            $logs = $this->getByUserId($user_id, $filters);
            
            require_once PROJECT_ROOT . '/vendor/autoload.php';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers
            $sheet->setCellValue('A1', 'تاریخ');
            $sheet->setCellValue('B1', 'کاربر');
            $sheet->setCellValue('C1', 'عملیات');
            $sheet->setCellValue('D1', 'جزئیات');

            // Add data
            $row = 2;
            foreach ($logs as $log) {
                $sheet->setCellValue('A' . $row, $log['created_at']);
                $sheet->setCellValue('B' . $row, $log['user_name']);
                $sheet->setCellValue('C' . $row, $log['action']);
                $sheet->setCellValue('D' . $row, $log['context']);
                $row++;
            }

            // Style headers
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);

            // Auto-size columns
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Output to browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="audit_log_' . date('Ymd') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            error_log("Error exporting audit log to Excel: " . $e->getMessage());
            return false;
        }
    }

    // Export to CSV
    public function exportToCSV($user_id, $filters = []) {
        try {
            $logs = $this->getByUserId($user_id, $filters);
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="audit_log_' . date('Ymd') . '.csv"');

            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write headers
            fputcsv($output, ['تاریخ', 'کاربر', 'عملیات', 'جزئیات']);

            // Write data
            foreach ($logs as $log) {
                fputcsv($output, [
                    $log['created_at'],
                    $log['user_name'],
                    $log['action'],
                    $log['context']
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            error_log("Error exporting audit log to CSV: " . $e->getMessage());
            return false;
        }
    }
}