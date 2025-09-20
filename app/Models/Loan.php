<?php
// app/Models/Loan.php

namespace App\Models;

use PDO;

class Loan {
    private $conn;
    private $table = 'loans';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all loans for user
    public function getByUserId($user_id, $filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";

        $params = [':user_id' => $user_id];

        if (!empty($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $query .= " AND (lender_name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY start_date DESC, created_at DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get single loan by ID + verify ownership
    public function findByIdAndUser($id, $user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create loan and generate installments
    public function create($user_id, $lender_name, $principal, $interest, $start_date, $term_months, $description = null) {
        try {
            $this->conn->beginTransaction();

            // Calculate installment amount
            $monthly_interest_rate = $interest / 100 / 12;
            if ($monthly_interest_rate == 0) {
                $installment_amount = $principal / $term_months;
            } else {
                $installment_amount = $principal * ($monthly_interest_rate * pow(1 + $monthly_interest_rate, $term_months)) / (pow(1 + $monthly_interest_rate, $term_months) - 1);
            }
            $installment_amount = round($installment_amount);

            // Insert loan
            $query = "INSERT INTO " . $this->table . " 
                      (user_id, lender_name, principal, interest, start_date, term_months, installment_amount, next_due_date, status, description, created_at, updated_at) 
                      VALUES (:user_id, :lender_name, :principal, :interest, :start_date, :term_months, :installment_amount, :next_due_date, 'active', :description, NOW(), NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':lender_name', $lender_name);
            $stmt->bindParam(':principal', $principal, PDO::PARAM_INT);
            $stmt->bindParam(':interest', $interest, PDO::PARAM_STR);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':term_months', $term_months, PDO::PARAM_INT);
            $stmt->bindParam(':installment_amount', $installment_amount, PDO::PARAM_INT);
            $stmt->bindParam(':next_due_date', $start_date);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            $loan_id = $this->conn->lastInsertId();

            // Generate installments
            $this->generateInstallments($loan_id, $start_date, $term_months, $installment_amount);

            $this->conn->commit();
            return $loan_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Loan creation failed: " . $e->getMessage());
            return false;
        }
    }

    // Generate installments for a loan
private function generateInstallments($loan_id, $start_date, $term_months, $installment_amount) {
    try {
        $current_date = new \DateTime($start_date);
        
        for ($i = 0; $i < $term_months; $i++) {
            $query = "INSERT INTO loan_installments (loan_id, due_date, amount, status, created_at) VALUES (:loan_id, :due_date, :amount, 'pending', NOW())";
            $stmt = $this->conn->prepare($query);
            
            // Use bindValue instead of bindParam for values
            $stmt->bindValue(':loan_id', $loan_id, PDO::PARAM_INT);
            $stmt->bindValue(':due_date', $current_date->format('Y-m-d'));
            $stmt->bindValue(':amount', $installment_amount, PDO::PARAM_INT);
            
            $stmt->execute();
            
            $current_date->modify('+1 month');
        }
    } catch (\Exception $e) {
        error_log("Error generating installments: " . $e->getMessage());
        throw $e;
    }
}

    // Update loan
    public function update($id, $user_id, $lender_name, $principal, $interest, $start_date, $term_months, $description = null) {
        try {
            $this->conn->beginTransaction();

            // Recalculate installment amount
            $monthly_interest_rate = $interest / 100 / 12;
            if ($monthly_interest_rate == 0) {
                $installment_amount = $principal / $term_months;
            } else {
                $installment_amount = $principal * ($monthly_interest_rate * pow(1 + $monthly_interest_rate, $term_months)) / (pow(1 + $monthly_interest_rate, $term_months) - 1);
            }
            $installment_amount = round($installment_amount);

            // Update loan
            $query = "UPDATE " . $this->table . " 
                      SET lender_name = :lender_name,
                          principal = :principal,
                          interest = :interest,
                          start_date = :start_date,
                          term_months = :term_months,
                          installment_amount = :installment_amount,
                          next_due_date = :start_date,
                          description = :description,
                          updated_at = NOW()
                      WHERE id = :id AND user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':lender_name', $lender_name);
            $stmt->bindParam(':principal', $principal, PDO::PARAM_INT);
            $stmt->bindParam(':interest', $interest, PDO::PARAM_STR);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':term_months', $term_months, PDO::PARAM_INT);
            $stmt->bindParam(':installment_amount', $installment_amount, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            // Delete existing installments
            $query = "DELETE FROM loan_installments WHERE loan_id = :loan_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':loan_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Generate new installments
            $this->generateInstallments($id, $start_date, $term_months, $installment_amount);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Loan update failed: " . $e->getMessage());
            return false;
        }
    }

    // Update loan status
    public function updateStatus($id, $user_id, $status) {
        if (!in_array($status, ['active', 'completed', 'cancelled'])) {
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

    // Delete loan (CASCADE will handle installments)
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get loan summary
    public function getSummary($user_id) {
        $query = "SELECT 
                    status,
                    COUNT(*) as count,
                    COALESCE(SUM(principal), 0) as total_principal
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id
                  GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}