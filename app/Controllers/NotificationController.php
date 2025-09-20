<?php
// app/Controllers/NotificationController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Notification;
use PDO;

class NotificationController {
    private $db;
    private $notification;

    public function __construct($db) {
        $this->db = $db;
        $this->notification = new Notification($db);
    }

    // Show notification center
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filters = [
            'type' => $_GET['type'] ?? '',
            'is_read' => $_GET['is_read'] ?? ''
        ];

        $notifications = $this->notification->getAllForUser($user_id, $filters);
        $count = $this->notification->getCountForUser($user_id);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/notifications/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Mark notification as read
    public function markRead() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        if ($this->notification->markAsRead($id, $user_id)) {
            $_SESSION['success'] = 'اعلان به عنوان خوانده شده علامت‌گذاری شد.';
        } else {
            $_SESSION['error'] = 'خطا در علامت‌گذاری اعلان.';
        }
        header('Location: /?action=notifications');
        exit;
    }

    // Mark all as read
    public function markAllRead() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        if ($this->notification->markAllAsRead($user_id)) {
            $_SESSION['success'] = 'تمام اعلان‌ها به عنوان خوانده شده علامت‌گذاری شدند.';
        } else {
            $_SESSION['error'] = 'خطا در علامت‌گذاری اعلان‌ها.';
        }
        header('Location: /?action=notifications');
        exit;
    }

    // Delete notification
    public function destroy() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        if ($this->notification->delete($id, $user_id)) {
            $_SESSION['success'] = 'اعلان با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف اعلان.';
        }
        header('Location: /?action=notifications');
        exit;
    }
}