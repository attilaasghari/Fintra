<?php
// public/index.php — Entry Point

session_start();

// Enable error display for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define PROJECT_ROOT — points to Fintra/ (parent of public/)
define('PROJECT_ROOT', dirname(__DIR__));

require_once PROJECT_ROOT . '/vendor/autoload.php';
require_once PROJECT_ROOT . '/config/database.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AccountController;
use App\Controllers\TransactionController;
use App\Controllers\CategoryController;
use App\Controllers\DebtController;
use App\Controllers\LoanController;
use App\Controllers\NotificationController;
use App\Controllers\ReportController;
use App\Controllers\BackupController;
use App\Controllers\AuditController;
use App\Controllers\HelpController;

$database = new Database();
$db = $database->getConnection();

$action = $_GET['action'] ?? 'landing';

// Public routes
if ($action === 'login') {
    $auth = new AuthController($db);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->login();
    } else {
        $auth->showLogin();
    }
} elseif ($action === 'register') {
    $auth = new AuthController($db);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->register();
    } else {
        $auth->showRegister();
    }
} elseif ($action === 'logout') {
    $auth = new AuthController($db);
    $auth->logout();
}
// Protected routes
elseif ($action === 'dashboard') {
    $controller = new DashboardController($db);
    $controller->index();
}
// Account routes
elseif ($action === 'accounts') {
    $controller = new AccountController($db);
    $controller->index();
}
elseif ($action === 'accounts.create') {
    $controller = new AccountController($db);
    $controller->create();
}
elseif ($action === 'accounts.store') {
    $controller = new AccountController($db);
    $controller->store();
}
elseif ($action === 'accounts.show') {
    $controller = new AccountController($db);
    $controller->show();
}
elseif ($action === 'accounts.edit') {
    $controller = new AccountController($db);
    $controller->edit();
}
elseif ($action === 'accounts.update') {
    $controller = new AccountController($db);
    $controller->update();
}
elseif ($action === 'accounts.destroy') {
    $controller = new AccountController($db);
    $controller->destroy();
}
// Transaction routes
elseif ($action === 'transactions') {
    $controller = new TransactionController($db);
    $controller->index();
}
elseif ($action === 'transactions.create') {
    $controller = new TransactionController($db);
    $controller->create();
}
elseif ($action === 'transactions.store') {
    $controller = new TransactionController($db);
    $controller->store();
}
elseif ($action === 'transactions.edit') {
    $controller = new TransactionController($db);
    $controller->edit();
}
elseif ($action === 'transactions.update') {
    $controller = new TransactionController($db);
    $controller->update();
}
elseif ($action === 'transactions.destroy') {
    $controller = new TransactionController($db);
    $controller->destroy();
}
// Category routes
elseif ($action === 'categories.account') {
    $controller = new CategoryController($db);
    $controller->accountIndex();
}
elseif ($action === 'categories.account.create') {
    $controller = new CategoryController($db);
    $controller->accountCreate();
}
elseif ($action === 'categories.account.edit') {
    $controller = new CategoryController($db);
    $controller->accountEdit();
}
elseif ($action === 'categories.account.delete') {
    $controller = new CategoryController($db);
    $controller->accountDelete();
}
elseif ($action === 'categories.transaction') {
    $controller = new CategoryController($db);
    $controller->transactionIndex();
}
elseif ($action === 'categories.transaction.create') {
    $controller = new CategoryController($db);
    $controller->transactionCreate();
}
elseif ($action === 'categories.transaction.edit') {
    $controller = new CategoryController($db);
    $controller->transactionEdit();
}
elseif ($action === 'categories.transaction.delete') {
    $controller = new CategoryController($db);
    $controller->transactionDelete();
}
// Debt routes
elseif ($action === 'debts') {
    $controller = new DebtController($db);
    $controller->index();
}
elseif ($action === 'debts.create') {
    $controller = new DebtController($db);
    $controller->create();
}
elseif ($action === 'debts.store') {
    $controller = new DebtController($db);
    $controller->store();
}
elseif ($action === 'debts.show') {
    $controller = new DebtController($db);
    $controller->show();
}
elseif ($action === 'debts.edit') {
    $controller = new DebtController($db);
    $controller->edit();
}
elseif ($action === 'debts.update') {
    $controller = new DebtController($db);
    $controller->update();
}
elseif ($action === 'debts.updateStatus') {
    $controller = new DebtController($db);
    $controller->updateStatus();
}
elseif ($action === 'debts.destroy') {
    $controller = new DebtController($db);
    $controller->destroy();
}
elseif ($action === 'debts.recordPayment') {
    $controller = new DebtController($db);
    $controller->recordPayment();
}
// Loan routes
elseif ($action === 'loans') {
    $controller = new LoanController($db);
    $controller->index();
}
elseif ($action === 'loans.create') {
    $controller = new LoanController($db);
    $controller->create();
}
elseif ($action === 'loans.store') {
    $controller = new LoanController($db);
    $controller->store();
}
elseif ($action === 'loans.show') {
    $controller = new LoanController($db);
    $controller->show();
}
elseif ($action === 'loans.edit') {
    $controller = new LoanController($db);
    $controller->edit();
}
elseif ($action === 'loans.update') {
    $controller = new LoanController($db);
    $controller->update();
}
elseif ($action === 'loans.updateStatus') {
    $controller = new LoanController($db);
    $controller->updateStatus();
}
elseif ($action === 'loans.destroy') {
    $controller = new LoanController($db);
    $controller->destroy();
}
elseif ($action === 'loans.markInstallmentPaid') {
    $controller = new LoanController($db);
    $controller->markInstallmentPaid();
}
// Notification routes
elseif ($action === 'notifications') {
    $controller = new NotificationController($db);
    $controller->index();
}
elseif ($action === 'notifications.markRead') {
    $controller = new NotificationController($db);
    $controller->markRead();
}
elseif ($action === 'notifications.markAllRead') {
    $controller = new NotificationController($db);
    $controller->markAllRead();
}
elseif ($action === 'notifications.destroy') {
    $controller = new NotificationController($db);
    $controller->destroy();
}
// Report routes
elseif ($action === 'reports') {
    $controller = new ReportController($db);
    $controller->index();
}
elseif ($action === 'reports.generate') {
    $controller = new ReportController($db);
    $controller->generate();
}
elseif ($action === 'reports.exportTransactions') {
    $controller = new ReportController($db);
    $controller->exportTransactions();
}
elseif ($action === 'reports.exportDebts') {
    $controller = new ReportController($db);
    $controller->exportDebts();
}
elseif ($action === 'reports.exportLoans') {
    $controller = new ReportController($db);
    $controller->exportLoans();
}
// Backup routes
elseif ($action === 'backup') {
    $controller = new BackupController($db);
    $controller->index();
}
elseif ($action === 'backup.create') {
    $controller = new BackupController($db);
    $controller->createBackup();
}
elseif ($action === 'backup.restore') {
    $controller = new BackupController($db);
    $controller->restoreBackup();
}
elseif ($action === 'backup.download') {
    $controller = new BackupController($db);
    $controller->downloadBackup();
}
elseif ($action === 'backup.delete') {
    $controller = new BackupController($db);
    $controller->deleteBackup();
}
// Audit routes
elseif ($action === 'audit') {
    $controller = new AuditController($db);
    $controller->index();
}
elseif ($action === 'audit.export') {
    $controller = new AuditController($db);
    $controller->export();
}
// Help route
elseif ($action === 'help') {
    $controller = new HelpController($db);
    $controller->index();
}
// Landing page (default)
elseif ($action === 'landing' || $action === '') {
    if (isset($_SESSION['user_id'])) {
        $controller = new DashboardController($db);
        $controller->index();
    } else {
        $auth = new AuthController($db);
        $auth->showLanding();
    }
}
// Default fallback
else {
    if (isset($_SESSION['user_id'])) {
        $controller = new DashboardController($db);
        $controller->index();
    } else {
        $auth = new AuthController($db);
        $auth->showLanding();
    }
}