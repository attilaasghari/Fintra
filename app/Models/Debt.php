<?php
// app/Models/Debt.php

namespace App\Models;

use PDO;

class Debt {
    private $conn;
    private $table = 'debts';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all debts for user
    public function getByUserId($user_id, $filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";

        $params = [':user_id' => $user_id];

        if (!empty($filters['type'])) {
            $query .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $query .= " AND (person_name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY due_date ASC, created_at DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get single debt by ID + verify ownership
    public function findByIdAndUser($id, $user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create debt
    public function create($user_id, $person_name, $type, $amount, $phone = null, $due_date = null, $description = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, person_name, phone, amount, due_date, type, status, description, created_at, updated_at) 
                  VALUES (:user_id, :person_name, :phone, :amount, :due_date, :type, 'unpaid', :description, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':person_name', $person_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    // Update debt
    public function update($id, $user_id, $person_name, $type, $amount, $phone = null, $due_date = null, $description = null) {
        $query = "UPDATE " . $this->table . " 
                  SET person_name = :person_name,
                      phone = :phone,
                      amount = :amount,
                      due_date = :due_date,
                      type = :type,
                      description = :description,
                      updated_at = NOW()
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':person_name', $person_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Update status (with validation)
    public function updateStatus($id, $user_id, $status) {
        if (!in_array($status, ['unpaid', 'partial', 'paid'])) {
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                  SET status = :status,
                      updated_at = NOW()
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // Delete debt
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get total amount by type and status
    public function getTotalsByType($user_id) {
        $query = "SELECT 
                    type,
                    status,
                    COALESCE(SUM(amount), 0) as total_amount,
                    COUNT(*) as count
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id
                  GROUP BY type, status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}