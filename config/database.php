<?php
// config/database.php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Get config from app.php or use defaults
        $config = require PROJECT_ROOT . '/config/app.php';
        
        $this->host = $config['db_host'] ?? 'localhost';
        $this->db_name = $config['db_name'] ?? 'fintra';
        $this->username = $config['db_username'] ?? 'archtek';
        $this->password = $config['db_password'] ?? '2002';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            die("خطا در اتصال به پایگاه داده");
        }
        return $this->conn;
    }
    
    // Get database configuration
    public function getConfig() {
        return [
            'host' => $this->host,
            'dbname' => $this->db_name,
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}