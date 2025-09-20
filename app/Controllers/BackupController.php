<?php
// app/Controllers/BackupController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use PDO;

class BackupController {
    private $db;
    private $backup_dir;

    public function __construct($db) {
        $this->db = $db;
        $config = require PROJECT_ROOT . '/config/app.php';
        $this->backup_dir = $config['backup_dir'];
        
        // Create backup directory if not exists
        if (!is_dir($this->backup_dir)) {
            mkdir($this->backup_dir, 0755, true);
        }
    }

    // Show backup/restore page
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        // Get list of backup files
        $backup_files = [];
        if (is_dir($this->backup_dir)) {
            $files = glob($this->backup_dir . 'backup_*.sql');
            foreach ($files as $file) {
                $backup_files[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'modified' => filemtime($file)
                ];
            }
            // Sort by modified date (newest first)
            usort($backup_files, function($a, $b) {
                return $b['modified'] - $a['modified'];
            });
        }

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/backup/index.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Generate backup
    public function createBackup() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $filepath = $this->backup_dir . $filename;

        // Try mysqldump first (faster, more reliable)
        $success = $this->createBackupWithMysqldump($filepath);

        // Fallback to PHP-based backup if mysqldump fails
        if (!$success) {
            $success = $this->createBackupWithPHP($filepath);
        }

        if ($success && file_exists($filepath) && filesize($filepath) > 0) {
            $_SESSION['success'] = 'پشتیبان‌گیری با موفقیت انجام شد. فایل: ' . $filename;
        } else {
            $_SESSION['error'] = 'خطا در ایجاد پشتیبان‌گیری. لطفا دوباره تلاش کنید.';
        }

        header('Location: /?action=backup');
        exit;
    }

    // Restore from backup
    public function restoreBackup() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filename = $_POST['filename'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
        
            if (empty($filename)) {
                $_SESSION['error'] = 'لطفا یک فایل پشتیبان را انتخاب کنید.';
                header('Location: /?action=backup');
                exit;
            }
        
            if (empty($confirm)) {
                $_SESSION['error'] = 'برای بازیابی، باید تأیید کنید که از این عملیات اطمینان دارید.';
                header('Location: /?action=backup');
                exit;
            }
        
            $filepath = $this->backup_dir . $filename;
        
            if (!file_exists($filepath)) {
                $_SESSION['error'] = 'فایل پشتیبان یافت نشد.';
                header('Location: /?action=backup');
                exit;
            }
        
            // Validate backup file
            if (filesize($filepath) < 100) {
                $_SESSION['error'] = 'فایل پشتیبان نامعتبر است (فایل خالی یا خراب).';
                header('Location: /?action=backup');
                exit;
            }
        
            try {
                // Disable foreign key checks
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            
                // Get list of tables to delete from (in correct order to avoid foreign key issues)
                $tables = [
                    'audit_logs',
                    'notifications',
                    'loan_installments',
                    'loans',
                    'debt_payments',
                    'debts',
                    'transactions',
                    'accounts',
                    'transaction_categories',
                    'account_categories',
                    'users'
                ];
            
                // Delete all records from tables
                foreach ($tables as $table) {
                    $this->db->exec("DELETE FROM `{$table}`");
                }
            
                // Reset auto-increment counters
                foreach ($tables as $table) {
                    $this->db->exec("ALTER TABLE `{$table}` AUTO_INCREMENT = 1");
                }
            
                // Read SQL file
                $sql = file_get_contents($filepath);
                
                // Split SQL by semicolon
                $statements = explode(';', $sql);
                
                // Execute each statement
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement) && 
                        !stripos($statement, 'CREATE DATABASE') && 
                        !stripos($statement, 'USE ') && 
                        !stripos($statement, 'DROP DATABASE')) {
                        try {
                            $this->db->exec($statement);
                        } catch (Exception $e) {
                            error_log("Failed to execute statement: {$statement}");
                            error_log("Error: " . $e->getMessage());
                            // Continue with next statement instead of stopping
                        }
                    }
                }
            
                // Re-enable foreign key checks
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
            
                $_SESSION['success'] = 'بازیابی داده‌ها با موفقیت انجام شد.';
            } catch (Exception $e) {
                // Re-enable foreign key checks in case of error
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
                
                $_SESSION['error'] = 'خطا در بازیابی داده‌ها: ' . $e->getMessage();
                error_log("Restore failed: " . $e->getMessage());
            }
        }
    
        header('Location: /?action=backup');
        exit;
    }

    // Download backup file
    public function downloadBackup() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filename = $_GET['filename'] ?? '';

        if (empty($filename)) {
            $_SESSION['error'] = 'لطفا یک فایل پشتیبان را انتخاب کنید.';
            header('Location: /?action=backup');
            exit;
        }

        $filepath = $this->backup_dir . $filename;

        if (!file_exists($filepath)) {
            $_SESSION['error'] = 'فایل پشتیبان یافت نشد.';
            header('Location: /?action=backup');
            exit;
        }

        // Set headers for download
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($filepath);
        exit;
    }

    // Delete backup file
    public function deleteBackup() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filename = $_GET['filename'] ?? '';

        if (empty($filename)) {
            $_SESSION['error'] = 'لطفا یک فایل پشتیبان را انتخاب کنید.';
            header('Location: /?action=backup');
            exit;
        }

        $filepath = $this->backup_dir . $filename;

        if (!file_exists($filepath)) {
            $_SESSION['error'] = 'فایل پشتیبان یافت نشد.';
            header('Location: /?action=backup');
            exit;
        }

        if (unlink($filepath)) {
            $_SESSION['success'] = 'فایل پشتیبان با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف فایل پشتیبان.';
        }

        header('Location: /?action=backup');
        exit;
    }

    // Create backup using mysqldump
    private function createBackupWithMysqldump($filepath) {
        try {
            // Get database config
            $database = new \Database();
            $config = $database->getConfig();

            $host = $config['host'];
            $dbname = $config['dbname'];
            $username = $config['username'];
            $password = $config['password'];

            // Build mysqldump command
            if (!empty($password)) {
                $command = "mysqldump --host={$host} --user={$username} --password={$password} {$dbname} > \"{$filepath}\" 2>&1";
            } else {
                $command = "mysqldump --host={$host} --user={$username} {$dbname} > \"{$filepath}\" 2>&1";
            }

            // Execute command
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);

            if ($return_var === 0 && file_exists($filepath) && filesize($filepath) > 100) {
                return true;
            } else {
                error_log("mysqldump failed with return code: {$return_var}, output: " . implode("\n", $output));
                return false;
            }
        } catch (Exception $e) {
            error_log("mysqldump failed: " . $e->getMessage());
            return false;
        }
    }

    // Create backup using PHP (fallback)
    private function createBackupWithPHP($filepath) {
        try {
            $fp = fopen($filepath, 'w');
            if (!$fp) {
                return false;
            }

            // Write header
            fwrite($fp, "-- Backup generated on " . date('Y-m-d H:i:s') . "\n");
            fwrite($fp, "-- Database: accounting_app\n\n");

            // Get all tables
            $tables = $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                // Write table structure
                $create_table = $this->db->query("SHOW CREATE TABLE `{$table}`")->fetch();
                fwrite($fp, $create_table[1] . ";\n\n");

                // Write table data
                $rows = $this->db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    $column_list = '`' . implode('`, `', $columns) . '`';
                    
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $value_list = implode(', ', $values);
                        fwrite($fp, "INSERT INTO `{$table}` ({$column_list}) VALUES ({$value_list});\n");
                    }
                    fwrite($fp, "\n");
                }
            }

            fclose($fp);
            return true;
        } catch (Exception $e) {
            error_log("PHP backup failed: " . $e->getMessage());
            return false;
        }
    }
}