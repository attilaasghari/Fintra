<?php
// app/Controllers/TransactionController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\Account;
use App\Models\AuditLog;
use PDO;

class TransactionController {
    private $db;
    private $transaction;
    private $transactionCategory;
    private $account;

    public function __construct($db) {
        $this->db = $db;
        $this->transaction = new Transaction($db);
        $this->transactionCategory = new TransactionCategory($db);
        $this->account = new Account($db);
    }

    // Show list of transactions
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        // Get filters from GET
        $filters = [
            'account_id' => $_GET['account_id'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'type' => $_GET['type'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $transactions = $this->transaction->getByUser($user_id, $filters);
        $categories = $this->transactionCategory->getByUserId($user_id);
        $accounts = $this->account->getByUserId($user_id);

        // Get totals for dashboard cards
        $total_income = $this->transaction->getTotalByType($user_id, 'income');
        $total_expense = $this->transaction->getTotalByType($user_id, 'expense');

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/transactions/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show create form
    public function create() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $accounts = $this->account->getByUserId($user_id);
        $categories = $this->transactionCategory->getByUserId($user_id);

        // Default account from URL parameter
        $default_account_id = $_GET['account_id'] ?? null;

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/transactions/create.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process create form
    public function store() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
    
        $account_id = (int)($_POST['account_id'] ?? 0);
        $type = $_POST['type'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $trans_date_str = $_POST['trans_date'] ?? \App\Helpers\JalaliHelper::now();
        $trans_date = \App\Helpers\JalaliHelper::toGregorian($trans_date_str);
        if ($trans_date === false) {
            $_SESSION['error'] = 'تاریخ وارد شده نامعتبر است. لطفا تاریخ را به صورت صحیح و با فرمت YYYY-MM-DD وارد کنید.';
            header('Location: /?action=transactions.create');
            exit;
        }
        $description = trim($_POST['description'] ?? '');
    
        // Validation
        if (empty($account_id)) {
            $_SESSION['error'] = 'لطفا یک حساب را انتخاب کنید.';
            header('Location: /?action=transactions.create');
            exit;
        }
        if (!in_array($type, ['income', 'expense'])) {
            $_SESSION['error'] = 'نوع تراکنش نامعتبر است.';
            header('Location: /?action=transactions.create');
            exit;
        }
        if ($amount <= 0) {
            $_SESSION['error'] = 'مبلغ باید بیشتر از صفر باشد.';
            header('Location: /?action=transactions.create');
            exit;
        }
    
        // Verify account belongs to user
        $account = $this->account->findByIdAndUser($account_id, $user_id);
        if (!$account) {
            $_SESSION['error'] = 'حساب مورد نظر معتبر نیست.';
            header('Location: /?action=transactions.create');
            exit;
        }
    
        if ($this->transaction->create($user_id, $account_id, $type, $amount, $trans_date, $category_id, $description)) {
            $_SESSION['success'] = 'تراکنش با موفقیت ثبت شد.';
            // Redirect to account view if came from there
            if (!empty($_POST['redirect_to_account'])) {
                header("Location: /?action=accounts.show&id=$account_id");
            } else {
                header('Location: /?action=transactions');
            }
            exit;
        } else {
            $_SESSION['error'] = 'خطا در ثبت تراکنش. لطفا دوباره تلاش کنید.';
            header('Location: /?action=transactions.create');
            exit;
        }
    }

    // Show edit form
    public function edit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $transaction = $this->transaction->findByIdAndUser($id, $user_id);
        if (!$transaction) {
            $_SESSION['error'] = 'تراکنش مورد نظر یافت نشد.';
            header('Location: /?action=transactions');
            exit;
        }

        $accounts = $this->account->getByUserId($user_id);
        $categories = $this->transactionCategory->getByUserId($user_id);

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/transactions/edit.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process update
    public function update() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);

        $account_id = (int)($_POST['account_id'] ?? 0);
        $type = $_POST['type'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $trans_date = $_POST['trans_date'] ?? date('Y-m-d');
        $trans_date = \App\Helpers\JalaliHelper::toGregorian($_POST['trans_date'] ?? date('Y-m/d'));
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (empty($account_id)) {
            $_SESSION['error'] = 'لطفا یک حساب را انتخاب کنید.';
            header("Location: /?action=transactions.edit&id=$id");
            exit;
        }
        if (!in_array($type, ['income', 'expense'])) {
            $_SESSION['error'] = 'نوع تراکنش نامعتبر است.';
            header("Location: /?action=transactions.edit&id=$id");
            exit;
        }
        if ($amount <= 0) {
            $_SESSION['error'] = 'مبلغ باید بیشتر از صفر باشد.';
            header("Location: /?action=transactions.edit&id=$id");
            exit;
        }

        // Verify account belongs to user
        $account = $this->account->findByIdAndUser($account_id, $user_id);
        if (!$account) {
            $_SESSION['error'] = 'حساب مورد نظر معتبر نیست.';
            header("Location: /?action=transactions.edit&id=$id");
            exit;
        }

        if ($this->transaction->update($id, $user_id, $account_id, $type, $amount, $trans_date, $category_id, $description)) {
            $_SESSION['success'] = 'تراکنش با موفقیت به‌روزرسانی شد.';
            header('Location: /?action=transactions');
            exit;
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی تراکنش.';
            header("Location: /?action=transactions.edit&id=$id");
            exit;
        }
    }

    // Delete transaction
    public function destroy() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    
        if ($id <= 0) {
            $_SESSION['error'] = 'شناسه تراکنش نامعتبر است.';
            $referrer = $_SERVER['HTTP_REFERER'] ?? '/?action=transactions';
            header("Location: $referrer");
            exit;
        }
    
        // Log before delete (optional)
        try {
            $audit = new AuditLog($this->db);
            $audit->create($user_id, 'delete_transaction', 'Transaction ID: ' . $id);
        } catch (\Exception $e) {
            error_log("Error logging transaction deletion: " . $e->getMessage());
            // Continue even if logging fails
        }
    
        if ($this->transaction->delete($id, $user_id)) {
            $_SESSION['success'] = 'تراکنش با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف تراکنش.';
        }
        $referrer = $_SERVER['HTTP_REFERER'] ?? '/?action=transactions';
        header("Location: $referrer");
        exit;
    }
}