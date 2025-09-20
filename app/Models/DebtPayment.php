<?php
// app/Models/DebtPayment.php

namespace App\Models;

use PDO;

class DebtPayment {
    private $conn;
    private $table = 'debt_payments';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all payments for a debt
    public function getByDebtId($debt_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE debt_id = :debt_id ORDER BY payment_date DESC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create payment
    public function create($debt_id, $user_id, $amount, $payment_date, $note = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (debt_id, user_id, amount, payment_date, note, created_at) 
                  VALUES (:debt_id, :user_id, :amount, :payment_date, :note, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':payment_date', $payment_date);
        $stmt->bindParam(':note', $note);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    // Get total paid for a debt
    public function getTotalPaid($debt_id) {
        $query = "SELECT COALESCE(SUM(amount), 0) as total_paid FROM " . $this->table . " WHERE debt_id = :debt_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) $row['total_paid'];
    }
}