<?php
// app/Models/LoanInstallment.php

namespace App\Models;

use PDO;

class LoanInstallment {
    private $conn;
    private $table = 'loan_installments';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all installments for a loan
    public function getByLoanId($loan_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE loan_id = :loan_id ORDER BY due_date ASC, created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mark installment as paid
    public function markAsPaid($id, $user_id, $paid_date = null) {
        if (!$paid_date) {
            $paid_date = date('Y-m-d');
        }

        $query = "UPDATE " . $this->table . " 
                  SET status = 'paid',
                      paid_date = :paid_date
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':paid_date', $paid_date);
        return $stmt->execute();
    }

    // Get payment statistics for a loan
    public function getPaymentStats($loan_id) {
        $query = "SELECT 
                    COUNT(*) as total_installments,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_installments,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_paid
                  FROM " . $this->table . " 
                  WHERE loan_id = :loan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get next due installment
    public function getNextDueInstallment($loan_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE loan_id = :loan_id AND status = 'pending' 
                  ORDER BY due_date ASC 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}