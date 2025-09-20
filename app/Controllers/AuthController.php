<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;
use PDO;

class AuthController {
    private $user;

    public function __construct($db) {
        $this->user = new User($db);
    }

    public function showLogin() {
        include PROJECT_ROOT . '/app/Views/auth/login.php';
    }

    public function showRegister() {
        include PROJECT_ROOT . '/app/Views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'لطفا ایمیل و رمز عبور را وارد کنید.';
                header('Location: /');
                exit;
            }

            $user = $this->user->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['success'] = 'با موفقیت وارد شدید.';
                header('Location: /?action=dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'ایمیل یا رمز عبور اشتباه است.';
                header('Location: /');
                exit;
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'لطفا تمام فیلدهای اجباری را پر کنید.';
                header('Location: /?action=register');
                exit;
            }

            if ($this->user->register($name, $email, $password, $phone)) {
                $_SESSION['success'] = 'ثبت نام با موفقیت انجام شد. لطفا وارد شوید.';
                header('Location: /');
                exit;
            } else {
                $_SESSION['error'] = 'ثبت نام با خطا مواجه شد. ممکن است ایمیل تکراری باشد.';
                header('Location: /?action=register');
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['success'] = 'با موفقیت خارج شدید.';
        header('Location: /');
        exit;
    }

    // Show landing page
    public function showLanding() {
        include PROJECT_ROOT . '/app/Views/auth/landing.php';
    }
}