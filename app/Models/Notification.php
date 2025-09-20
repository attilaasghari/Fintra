<?php
// app/Models/Notification.php

namespace App\Models;

use PDO;

class Notification {
    private $conn;
    private $table = 'notifications';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get unread reminders for next X days
    public function getUpcomingReminders($user_id, $days) {
        $date_limit = date('Y-m-d', strtotime("+$days days"));
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                    AND created_at <= :date_limit 
                    AND is_read = 0
                  ORDER BY created_at ASC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_limit', $date_limit, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all notifications for user
    public function getAllForUser($user_id, $filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";

        $params = [':user_id' => $user_id];

        if (!empty($filters['type'])) {
            $query .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['is_read'])) {
            $query .= " AND is_read = :is_read";
            $params[':is_read'] = $filters['is_read'];
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create notification
    public function create($user_id, $type, $title, $body) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, type, title, body, is_read, created_at) 
                  VALUES (:user_id, :type, :title, :body, 0, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        return $stmt->execute();
    }

    // Mark as read
    public function markAsRead($id, $user_id) {
        $query = "UPDATE " . $this->table . " 
                  SET is_read = 1 
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mark all as read
    public function markAllAsRead($user_id) {
        $query = "UPDATE " . $this->table . " 
                  SET is_read = 1 
                  WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete notification
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get notification count for user
    public function getCountForUser($user_id) {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread
                  FROM " . $this->table . " 
                  WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}