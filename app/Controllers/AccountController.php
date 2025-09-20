<?php
// app/Controllers/AccountController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\Transaction;
use PDO;

class AccountController {
    private $db;
    private $account;
    private $accountCategory;

    public function __construct($db) {
        $this->db = $db;
        $this->account = new Account($db);
        $this->accountCategory = new AccountCategory($db);
    }

    // Show list of accounts
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $accounts = $this->account->getByUserId($user_id);
        // Add balance to each account
        foreach ($accounts as &$acc) {
            $acc['balance'] = $this->account->getBalance($acc['id']);
        }
        unset($acc); // Prevent reference bug

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/accounts/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show create form
    public function create() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $categories = $this->accountCategory->getByUserId($user_id);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/accounts/create.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process create form
    public function store() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $title = trim($_POST['title'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $account_number = trim($_POST['account_number'] ?? '');
        $card_number = trim($_POST['card_number'] ?? '');
        $initial_balance = (int)($_POST['initial_balance'] ?? 0);

        if (empty($title)) {
            $_SESSION['error'] = 'لطفا عنوان حساب را وارد کنید.';
            header('Location: /?action=accounts.create');
            exit;
        }

        if ($this->account->create($user_id, $title, $category_id, $account_number, $card_number, $initial_balance)) {
            $_SESSION['success'] = 'حساب جدید با موفقیت ایجاد شد.';
            header('Location: /?action=accounts');
            exit;
        } else {
            $_SESSION['error'] = 'خطا در ایجاد حساب. لطفا دوباره تلاش کنید.';
            header('Location: /?action=accounts.create');
            exit;
        }
    }

    // Show account detail
    public function show() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $account = $this->account->findByIdAndUser($id, $user_id);
        if (!$account) {
            $_SESSION['error'] = 'حساب مورد نظر یافت نشد.';
            header('Location: /?action=accounts');
            exit;
        }

        $balance = $this->account->getBalance($id);

        // Get real transactions and totals for this account
        $transactionModel = new Transaction($this->db); 
        $transactions = $transactionModel->getByUser($user_id, ['account_id' => $id]);
        $totals = $transactionModel->getAccountTotals($id);
        $total_income = (int)$totals['total_income'];
        $total_expense = (int)$totals['total_expense'];

        // Pass all data to view
        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/accounts/view.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show edit form
    public function edit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $account = $this->account->findByIdAndUser($id, $user_id);
        if (!$account) {
            $_SESSION['error'] = 'حساب مورد نظر یافت نشد.';
            header('Location: /?action=accounts');
            exit;
        }

        $categories = $this->accountCategory->getByUserId($user_id);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/accounts/edit.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process update
    public function update() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);

        $title = trim($_POST['title'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $account_number = trim($_POST['account_number'] ?? '');
        $card_number = trim($_POST['card_number'] ?? '');
        $initial_balance = (int)($_POST['initial_balance'] ?? 0);

        if (empty($title)) {
            $_SESSION['error'] = 'لطفا عنوان حساب را وارد کنید.';
            header("Location: /?action=accounts.edit&id=$id");
            exit;
        }

        if ($this->account->update($id, $user_id, $title, $category_id, $account_number, $card_number, $initial_balance)) {
            $_SESSION['success'] = 'حساب با موفقیت به‌روزرسانی شد.';
            header("Location: /?action=accounts.show&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی حساب.';
            header("Location: /?action=accounts.edit&id=$id");
            exit;
        }
    }

    // Delete account
    public function destroy() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($this->account->delete($id, $user_id)) {
            $_SESSION['success'] = 'حساب با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف حساب.';
        }
        header('Location: /?action=accounts');
        exit;
    }
}