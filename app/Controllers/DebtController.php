<?php
// app/Controllers/DebtController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Debt;
use App\Models\DebtPayment;
use PDO;

class DebtController {
    private $db;
    private $debt;
    private $debtPayment;

    public function __construct($db) {
        $this->db = $db;
        $this->debt = new Debt($db);
        $this->debtPayment = new DebtPayment($db);
    }

    // Show list of debts
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filters = [
            'type' => $_GET['type'] ?? '',
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $debts = $this->debt->getByUserId($user_id, $filters);
        $totals = $this->debt->getTotalsByType($user_id);

        // Calculate summary
        $summary = [
            'total_debt_unpaid' => 0,
            'total_debt_partial' => 0,
            'total_debt_paid' => 0,
            'total_credit_unpaid' => 0,
            'total_credit_partial' => 0,
            'total_credit_paid' => 0
        ];

        foreach ($totals as $t) {
            $key = 'total_' . $t['type'] . '_' . $t['status'];
            $summary[$key] = (int)$t['total_amount'];
        }

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/debts/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show create form
    public function create() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/debts/create.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process create form
    public function store() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $person_name = trim($_POST['person_name'] ?? '');
        $type = $_POST['type'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        $phone = trim($_POST['phone'] ?? '');
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $due_date = !empty($_POST['due_date']) ? \App\Helpers\JalaliHelper::toGregorian($_POST['due_date']) : null;
        if (!empty($_POST['due_date']) && $due_date === false) {
            $_SESSION['error'] = 'تاریخ سررسید وارد شده نامعتبر است. لطفا تاریخ را به صورت صحیح و با فرمت YYYY-MM-DD وارد کنید.';
            header('Location: /?action=debts.create');
            exit;
        }
        $description = trim($_POST['description'] ?? '');

        if (empty($person_name)) {
            $_SESSION['error'] = 'لطفا نام شخص را وارد کنید.';
            header('Location: /?action=debts.create');
            exit;
        }
        if (!in_array($type, ['debt', 'credit'])) {
            $_SESSION['error'] = 'نوع بدهی/طلب نامعتبر است.';
            header('Location: /?action=debts.create');
            exit;
        }
        if ($amount <= 0) {
            $_SESSION['error'] = 'مبلغ باید بیشتر از صفر باشد.';
            header('Location: /?action=debts.create');
            exit;
        }

        if ($this->debt->create($user_id, $person_name, $type, $amount, $phone, $due_date, $description)) {
            $_SESSION['success'] = 'بدهی/طلب جدید با موفقیت ثبت شد.';
            header('Location: /?action=debts');
            exit;
        } else {
            $_SESSION['error'] = 'خطا در ثبت بدهی/طلب.';
            header('Location: /?action=debts.create');
            exit;
        }
    }

    // Show debt detail
    public function show() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $debt = $this->debt->findByIdAndUser($id, $user_id);
        if (!$debt) {
            $_SESSION['error'] = 'بدهی/طلب مورد نظر یافت نشد.';
            header('Location: /?action=debts');
            exit;
        }

        $payments = $this->debtPayment->getByDebtId($id);
        $total_paid = $this->debtPayment->getTotalPaid($id);
        $remaining = $debt['amount'] - $total_paid;

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/debts/view.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show edit form
    public function edit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $debt = $this->debt->findByIdAndUser($id, $user_id);
        if (!$debt) {
            $_SESSION['error'] = 'بدهی/طلب مورد نظر یافت نشد.';
            header('Location: /?action=debts');
            exit;
        }

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/debts/edit.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process update
    public function update() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);

        $person_name = trim($_POST['person_name'] ?? '');
        $type = $_POST['type'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        $phone = trim($_POST['phone'] ?? '');
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $due_date = !empty($_POST['due_date']) ? \App\Helpers\JalaliHelper::toGregorian($_POST['due_date']) : null;
        $description = trim($_POST['description'] ?? '');

        if (empty($person_name)) {
            $_SESSION['error'] = 'لطفا نام شخص را وارد کنید.';
            header("Location: /?action=debts.edit&id=$id");
            exit;
        }
        if (!in_array($type, ['debt', 'credit'])) {
            $_SESSION['error'] = 'نوع بدهی/طلب نامعتبر است.';
            header("Location: /?action=debts.edit&id=$id");
            exit;
        }
        if ($amount <= 0) {
            $_SESSION['error'] = 'مبلغ باید بیشتر از صفر باشد.';
            header("Location: /?action=debts.edit&id=$id");
            exit;
        }

        if ($this->debt->update($id, $user_id, $person_name, $type, $amount, $phone, $due_date, $description)) {
            $_SESSION['success'] = 'بدهی/طلب با موفقیت به‌روزرسانی شد.';
            header("Location: /?action=debts.show&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی بدهی/طلب.';
            header("Location: /?action=debts.edit&id=$id");
            exit;
        }
    }

    // Update status with confirmation
    public function updateStatus() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['unpaid', 'partial', 'paid'])) {
            $_SESSION['error'] = 'وضعیت نامعتبر است.';
            header('Location: /?action=debts');
            exit;
        }

        $debt = $this->debt->findByIdAndUser($id, $user_id);
        if (!$debt) {
            $_SESSION['error'] = 'بدهی/طلب مورد نظر یافت نشد.';
            header('Location: /?action=debts');
            exit;
        }

        // For 'paid' status, verify that amount is fully paid
        if ($status === 'paid') {
            $total_paid = $this->debtPayment->getTotalPaid($id);
            if ($total_paid < $debt['amount']) {
                $_SESSION['error'] = 'برای تغییر وضعیت به "پرداخت شده"، باید کل مبلغ پرداخت شده باشد.';
                header("Location: /?action=debts.show&id=$id");
                exit;
            }
        }

        if ($this->debt->updateStatus($id, $user_id, $status)) {
            $_SESSION['success'] = 'وضعیت بدهی/طلب با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی وضعیت.';
        }
        header("Location: /?action=debts.show&id=$id");
        exit;
    }

    // Delete debt
    public function destroy() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($this->debt->delete($id, $user_id)) {
            $_SESSION['success'] = 'بدهی/طلب با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف بدهی/طلب.';
        }
        header('Location: /?action=debts');
        exit;
    }

    // Record payment
    public function recordPayment() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $debt_id = (int)($_POST['debt_id'] ?? 0);
        $amount = (int)($_POST['amount'] ?? 0);
        $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
        $payment_date = \App\Helpers\JalaliHelper::toGregorian($_POST['payment_date'] ?? \App\Helpers\JalaliHelper::now());
        $note = trim($_POST['note'] ?? '');

        if ($amount <= 0) {
            $_SESSION['error'] = 'مبلغ پرداختی باید بیشتر از صفر باشد.';
            header("Location: /?action=debts.show&id=$debt_id");
            exit;
        }

        $debt = $this->debt->findByIdAndUser($debt_id, $user_id);
        if (!$debt) {
            $_SESSION['error'] = 'بدهی/طلب مورد نظر یافت نشد.';
            header('Location: /?action=debts');
            exit;
        }

        // Check if payment exceeds remaining amount
        $total_paid = $this->debtPayment->getTotalPaid($debt_id);
        $remaining = $debt['amount'] - $total_paid;
        if ($amount > $remaining) {
            $_SESSION['error'] = 'مبلغ پرداختی نمی‌تواند بیشتر از مبلغ باقیمانده (' . number_format($remaining) . ' تومان) باشد.';
            header("Location: /?action=debts.show&id=$debt_id");
            exit;
        }

        if ($this->debtPayment->create($debt_id, $user_id, $amount, $payment_date, $note)) {
            // Update status if needed
            $new_total_paid = $total_paid + $amount;
            if ($new_total_paid == $debt['amount']) {
                $this->debt->updateStatus($debt_id, $user_id, 'paid');
            } elseif ($new_total_paid > 0 && $debt['status'] === 'unpaid') {
                $this->debt->updateStatus($debt_id, $user_id, 'partial');
            }

            $_SESSION['success'] = 'پرداخت با موفقیت ثبت شد.';
        } else {
            $_SESSION['error'] = 'خطا در ثبت پرداخت.';
        }
        header("Location: /?action=debts.show&id=$debt_id");
        exit;
    }
}