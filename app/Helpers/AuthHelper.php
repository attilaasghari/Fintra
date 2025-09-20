<?php
// app/Helpers/AuthHelper.php

namespace App\Helpers;

class AuthHelper {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'لطفاً ابتدا وارد سامانه شوید.';
            header('Location: /fintre/');
            exit;
        }
    }

    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getCurrentUserName() {
        return $_SESSION['user_name'] ?? 'کاربر';
    }
}