<?php
// app/Models/Transaction.php

namespace App\Models;

use PDO;

class Transaction {
    private $conn;
    private $table = 'transactions';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get total amount by type (income/expense) for user
    public function getTotalByType($user_id, $type) {
        $query = "SELECT COALESCE(SUM(amount), 0) as total 
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id AND type = :type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) $row['total'];
    }

    // Get transactions for user with filters
    public function getByUser($user_id, $filters = []) {
        $query = "SELECT t.*, tc.name as category_name, a.title as account_title
                  FROM " . $this->table . " t
                  LEFT JOIN transaction_categories tc ON t.category_id = tc.id
                  LEFT JOIN accounts a ON t.account_id = a.id
                  WHERE t.user_id = :user_id";

        $params = [':user_id' => $user_id];

        // Apply filters
        if (!empty($filters['account_id'])) {
            $query .= " AND t.account_id = :account_id";
            $params[':account_id'] = $filters['account_id'];
        }
        if (!empty($filters['category_id'])) {
            $query .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['type'])) {
            $query .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['start_date'])) {
            $gregorian_start = \App\Helpers\JalaliHelper::toGregorian($filters['start_date']);
            $query .= " AND t.trans_date >= :start_date";
            $params[':start_date'] = $gregorian_start;
        }
        if (!empty($filters['end_date'])) {
            $gregorian_end = \App\Helpers\JalaliHelper::toGregorian($filters['end_date']);
            $query .= " AND t.trans_date <= :end_date";
            $params[':end_date'] = $gregorian_end;
        }
        if (!empty($filters['search'])) {
            $query .= " AND (t.description LIKE :search OR a.title LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY t.trans_date DESC, t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get single transaction by ID + verify ownership
    public function findByIdAndUser($id, $user_id) {
        $query = "SELECT t.*, tc.name as category_name, a.title as account_title
                  FROM " . $this->table . " t
                  LEFT JOIN transaction_categories tc ON t.category_id = tc.id
                  LEFT JOIN accounts a ON t.account_id = a.id
                  WHERE t.id = :id AND t.user_id = :user_id
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create transaction
    public function create($user_id, $account_id, $type, $amount, $trans_date, $category_id = null, $description = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, account_id, type, category_id, amount, trans_date, description, created_at, updated_at) 
                  VALUES (:user_id, :account_id, :type, :category_id, :amount, :trans_date, :description, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':trans_date', $trans_date, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    // Update transaction
    public function update($id, $user_id, $account_id, $type, $amount, $trans_date, $category_id = null, $description = null) {
        $query = "UPDATE " . $this->table . " 
                  SET account_id = :account_id,
                      type = :type,
                      category_id = :category_id,
                      amount = :amount,
                      trans_date = :trans_date,
                      description = :description,
                      updated_at = NOW()
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':trans_date', $trans_date, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Delete transaction
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get totals for a specific account
    public function getAccountTotals($account_id) {
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                    COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
                  FROM " . $this->table . " 
                  WHERE account_id = :account_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get total amount by type and month (YYYY-MM)
    public function getTotalByTypeAndMonth($user_id, $type, $month) {
        $query = "SELECT COALESCE(SUM(amount), 0) as total 
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                    AND type = :type 
                    AND DATE_FORMAT(trans_date, '%Y-%m') = :month";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) $row['total'];
    }
}