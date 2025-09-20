<?php
// app/Controllers/AuditController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\AuditLog;
use PDO;

class AuditController {
    private $db;
    private $auditLog;

    public function __construct($db) {
        $this->db = $db;
        $this->auditLog = new AuditLog($db);
    }

    // Show audit log page
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filters = [
            'action' => $_GET['action'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        $logs = $this->auditLog->getByUserId($user_id, $filters);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/audit/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Export audit log
    public function export() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $format = $_GET['format'] ?? 'excel';
        $filters = [
            'action' => $_GET['action'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        if ($format === 'csv') {
            $this->auditLog->exportToCSV($user_id, $filters);
        } else {
            $this->auditLog->exportToExcel($user_id, $filters);
        }
    }
}