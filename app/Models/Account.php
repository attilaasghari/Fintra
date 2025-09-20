<?php
// app/Models/Account.php

namespace App\Models;

use PDO;

class Account {
    private $conn;
    private $table = 'accounts';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all accounts for a user
    public function getByUserId($user_id) {
        $query = "SELECT a.*, ac.name as category_name 
                  FROM " . $this->table . " a
                  LEFT JOIN account_categories ac ON a.category_id = ac.id
                  WHERE a.user_id = :user_id
                  ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get single account by ID + verify ownership
    public function findByIdAndUser($id, $user_id) {
        $query = "SELECT a.*, ac.name as category_name 
                  FROM " . $this->table . " a
                  LEFT JOIN account_categories ac ON a.category_id = ac.id
                  WHERE a.id = :id AND a.user_id = :user_id
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create new account
    public function create($user_id, $title, $category_id = null, $account_number = null, $card_number = null, $initial_balance = 0) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, category_id, title, account_number, card_number, initial_balance, created_at, updated_at) 
                  VALUES (:user_id, :category_id, :title, :account_number, :card_number, :initial_balance, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':account_number', $account_number);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':initial_balance', $initial_balance, PDO::PARAM_INT);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    // Update account
    public function update($id, $user_id, $title, $category_id = null, $account_number = null, $card_number = null, $initial_balance = 0) {
        $query = "UPDATE " . $this->table . " 
                  SET category_id = :category_id, 
                      title = :title, 
                      account_number = :account_number, 
                      card_number = :card_number, 
                      initial_balance = :initial_balance, 
                      updated_at = NOW()
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':account_number', $account_number);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':initial_balance', $initial_balance, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete account (CASCADE will handle transactions via FK)
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Calculate current balance for an account
    public function getBalance($account_id) {
        $query = "SELECT 
                    a.initial_balance + 
                    COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0) - 
                    COALESCE(SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0) AS balance
                  FROM accounts a
                  LEFT JOIN transactions t ON a.id = t.account_id
                  WHERE a.id = :account_id
                  GROUP BY a.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['balance'] ?? 0);
    }
}