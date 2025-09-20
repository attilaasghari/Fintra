<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\AuditLog;
use PDO;

class DashboardController {
    private $db;
    private $transaction;
    private $notification;

    public function __construct($db) {
        $this->db = $db;
        $this->transaction = new Transaction($db);
        $this->notification = new Notification($db);
    }

    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        // Get totals
        $total_income = $this->transaction->getTotalByType($user_id, 'income');
        $total_expense = $this->transaction->getTotalByType($user_id, 'expense');

        // Get upcoming reminders
        $upcoming_reminders = $this->notification->getUpcomingReminders($user_id, 7);

        // Get notification count
        $notification_count = $this->notification->getCountForUser($user_id);

        // Get recent activities
        $auditLog = new AuditLog($this->db);
        $recent_activities = $auditLog->getRecentActivities($user_id, 5);

        // Prepare chart data for last 6 months
        $chart_labels = [];
        $chart_income = [];
        $chart_expense = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $month_date = new \DateTime($month . '-01');
            $jalali_month = \App\Helpers\JalaliHelper::format($month_date->format('Y-m-d'), 'Y F');
                
            $chart_labels[] = $jalali_month;
                
            // Get income for this month
            $income = $this->transaction->getTotalByTypeAndMonth($user_id, 'income', $month);
            $chart_income[] = (int)$income;
                
            // Get expense for this month
            $expense = $this->transaction->getTotalByTypeAndMonth($user_id, 'expense', $month);
            $chart_expense[] = (int)$expense;
        }

        // Pass data to view
        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/dashboard/index.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Helper to convert month number to Persian name
    private function getPersianMonthName($month_num) {
        $months = [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند'
        ];
        return $months[$month_num] ?? "ماه $month_num";
    }
}