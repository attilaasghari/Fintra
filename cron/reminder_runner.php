<?php
// cron/reminder_runner.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\Debt;
use App\Models\Notification;

// Get database connection
$database = new Database();
$db = $database->getConnection();

$loan = new Loan($db);
$loanInstallment = new LoanInstallment($db);
$debt = new Debt($db);
$notification = new Notification($db);

echo "Starting reminder generation...\n";

// Get all users (for simplicity, in real app you might want to process in batches)
$users = $db->query("SELECT id FROM users")->fetchAll();

foreach ($users as $user) {
    $user_id = $user['id'];
    echo "Processing user ID: $user_id\n";

    // Check for upcoming loan installments (next 7 days)
    $upcoming_installments = $db->prepare("
        SELECT li.*, l.lender_name, l.id as loan_id 
        FROM loan_installments li 
        JOIN loans l ON li.loan_id = l.id 
        WHERE l.user_id = ? 
          AND li.status = 'pending' 
          AND li.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ");
    $upcoming_installments->execute([$user_id]);
    $installments = $upcoming_installments->fetchAll();

    foreach ($installments as $inst) {
        // Check if notification already exists
        $check = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND type = 'loan_installment' AND body LIKE ?");
        $check->execute([$user_id, "%installment_id:{$inst['id']}%"]);
        $exists = $check->fetch()['count'];

        if (!$exists) {
            $title = "یادآوری قسط وام";
            $body = "قسط ماهانه به مبلغ " . number_format($inst['amount']) . " تومان از وام " . $inst['lender_name'] . " در تاریخ " . $inst['due_date'] . " سررسید دارد. installment_id:{$inst['id']}";
            
            if ($notification->create($user_id, 'loan_installment', $title, $body)) {
                echo "Created loan installment reminder for user $user_id, installment {$inst['id']}\n";
            }
        }
    }

    // Check for upcoming debt due dates (next 7 days)
    $upcoming_debts = $db->prepare("
        SELECT * FROM debts 
        WHERE user_id = ? 
          AND status IN ('unpaid', 'partial') 
          AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ");
    $upcoming_debts->execute([$user_id]);
    $debts = $upcoming_debts->fetchAll();

    foreach ($debts as $d) {
        // Check if notification already exists
        $check = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND type = 'debt_due' AND body LIKE ?");
        $check->execute([$user_id, "%debt_id:{$d['id']}%"]);
        $exists = $check->fetch()['count'];

        if (!$exists) {
            $debt_type = $d['type'] === 'debt' ? 'بدهی' : 'طلب';
            $title = "یادآوری سررسید $debt_type";
            $body = "$debt_type به نام " . $d['person_name'] . " به مبلغ " . number_format($d['amount']) . " تومان در تاریخ " . $d['due_date'] . " سررسید دارد. debt_id:{$d['id']}";
            
            if ($notification->create($user_id, 'debt_due', $title, $body)) {
                echo "Created debt reminder for user $user_id, debt {$d['id']}\n";
            }
        }
    }
}

echo "Reminder generation completed.\n";