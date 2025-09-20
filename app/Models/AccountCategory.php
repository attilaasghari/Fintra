<?php
// app/Models/AccountCategory.php

namespace App\Models;

use PDO;

class AccountCategory {
    private $conn;
    private $table = 'account_categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all categories for user
    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create category
    public function create($user_id, $name) {
        $query = "INSERT INTO " . $this->table . " (user_id, name, created_at) VALUES (:user_id, :name, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    // Update category
    public function update($id, $user_id, $name) {
        $query = "UPDATE " . $this->table . " SET name = :name WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    // Delete category (SET NULL on accounts via FK)
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}