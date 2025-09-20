<?php
// app/Controllers/CategoryController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\AccountCategory;
use App\Models\TransactionCategory;
use PDO;

class CategoryController {
    private $db;
    private $accountCategory;
    private $transactionCategory;

    public function __construct($db) {
        $this->db = $db;
        $this->accountCategory = new AccountCategory($db);
        $this->transactionCategory = new TransactionCategory($db);
    }

    // Show account categories
    public function accountIndex() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $categories = $this->accountCategory->getByUserId($user_id);

        // Add usage count for each account category
        foreach ($categories as &$cat) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM accounts WHERE category_id = ?");
            $stmt->execute([$cat['id']]);
            $cat['usage_count'] = $stmt->fetch()['count'];
        }
        unset($cat); // Prevent reference bug

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/categories/account_categories.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Create account category
    public function accountCreate() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'لطفا نام دسته‌بندی را وارد کنید.';
            header('Location: /?action=categories.account');
            exit;
        }

        if ($this->accountCategory->create($user_id, $name)) {
            $_SESSION['success'] = 'دسته‌بندی حساب جدید با موفقیت ایجاد شد.';
        } else {
            $_SESSION['error'] = 'خطا در ایجاد دسته‌بندی. ممکن است نام تکراری باشد.';
        }
        header('Location: /?action=categories.account');
        exit;
    }

    // Edit account category
    public function accountEdit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'لطفا نام دسته‌بندی را وارد کنید.';
            header('Location: /?action=categories.account');
            exit;
        }

        if ($this->accountCategory->update($id, $user_id, $name)) {
            $_SESSION['success'] = 'دسته‌بندی حساب با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی دسته‌بندی.';
        }
        header('Location: /?action=categories.account');
        exit;
    }

    // Delete account category
    public function accountDelete() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        // Accept ID from POST or GET
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'شناسه دسته‌بندی نامعتبر است.';
            header('Location: /?action=categories.account');
            exit;
        }

        if ($this->accountCategory->delete($id, $user_id)) {
            $_SESSION['success'] = 'دسته‌بندی حساب با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف دسته‌بندی.';
        }
        header('Location: /?action=categories.account');
        exit;
    }

    // Show transaction categories
    public function transactionIndex() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $categories = $this->transactionCategory->getByUserId($user_id);

        // Add usage count for each transaction category
        foreach ($categories as &$cat) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM transactions WHERE category_id = ?");
            $stmt->execute([$cat['id']]);
            $cat['usage_count'] = $stmt->fetch()['count'];
        }
        unset($cat); // Prevent reference bug

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/categories/transaction_categories.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Create transaction category
    public function transactionCreate() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'لطفا نام دسته‌بندی را وارد کنید.';
            header('Location: /?action=categories.transaction');
            exit;
        }

        if ($this->transactionCategory->create($user_id, $name)) {
            $_SESSION['success'] = 'دسته‌بندی تراکنش جدید با موفقیت ایجاد شد.';
        } else {
            $_SESSION['error'] = 'خطا در ایجاد دسته‌بندی. ممکن است نام تکراری باشد.';
        }
        header('Location: /?action=categories.transaction');
        exit;
    }

    // Edit transaction category
    public function transactionEdit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'لطفا نام دسته‌بندی را وارد کنید.';
            header('Location: /?action=categories.transaction');
            exit;
        }

        if ($this->transactionCategory->update($id, $user_id, $name)) {
            $_SESSION['success'] = 'دسته‌بندی تراکنش با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی دسته‌بندی.';
        }
        header('Location: /?action=categories.transaction');
        exit;
    }

    // Delete transaction category
    public function transactionDelete() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        
        // Accept ID from POST or GET
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    
        if ($id <= 0) {
            $_SESSION['error'] = 'شناسه دسته‌بندی نامعتبر است.';
            header('Location: /?action=categories.transaction');
            exit;
        }
    
        if ($this->transactionCategory->delete($id, $user_id)) {
            $_SESSION['success'] = 'دسته‌بندی تراکنش با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف دسته‌بندی.';
        }
        header('Location: /?action=categories.transaction');
        exit;
    }
}