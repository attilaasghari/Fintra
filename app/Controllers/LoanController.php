<?php
// app/Controllers/LoanController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Loan;
use App\Models\LoanInstallment;
use PDO;

class LoanController {
    private $db;
    private $loan;
    private $loanInstallment;

    public function __construct($db) {
        $this->db = $db;
        $this->loan = new Loan($db);
        $this->loanInstallment = new LoanInstallment($db);
    }

    // Show list of loans
    public function index() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $loans = $this->loan->getByUserId($user_id, $filters);
        $summary = $this->loan->getSummary($user_id);

        // Calculate summary
        $summary_data = [
            'active_count' => 0,
            'active_total' => 0,
            'completed_count' => 0,
            'completed_total' => 0,
            'cancelled_count' => 0,
            'cancelled_total' => 0
        ];

        foreach ($summary as $s) {
            $summary_data[$s['status'] . '_count'] = (int)$s['count'];
            $summary_data[$s['status'] . '_total'] = (int)$s['total_principal'];
        }

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/loans/list.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show create form
    public function create() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/loans/create.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

   // Process create form
    public function store() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
    
        $lender_name = trim($_POST['lender_name'] ?? '');
        $principal = (int)($_POST['principal'] ?? 0);
        $interest = (float)($_POST['interest'] ?? 0);
        $start_date_str = $_POST['start_date'] ?? \App\Helpers\JalaliHelper::now();
        $start_date = \App\Helpers\JalaliHelper::toGregorian($start_date_str);
        $term_months = (int)($_POST['term_months'] ?? 0);
        $description = trim($_POST['description'] ?? '');
    
        if (empty($lender_name)) {
            $_SESSION['error'] = 'لطفا نام وام‌دهنده را وارد کنید.';
            header('Location: /?action=loans.create');
            exit;
        }
        if ($principal <= 0) {
            $_SESSION['error'] = 'مبلغ اصلی باید بیشتر از صفر باشد.';
            header('Location: /?action=loans.create');
            exit;
        }
        if ($term_months <= 0) {
            $_SESSION['error'] = 'تعداد اقساط باید بیشتر از صفر باشد.';
            header('Location: /?action=loans.create');
            exit;
        }
    
        if ($this->loan->create($user_id, $lender_name, $principal, $interest, $start_date, $term_months, $description)) {
            $_SESSION['success'] = 'وام جدید با موفقیت ثبت شد و اقساط آن ایجاد گردید.';
            header('Location: /?action=loans');
            exit;
        } else {
            $_SESSION['error'] = 'خطا در ثبت وام. لطفا دوباره تلاش کنید.';
            header('Location: /?action=loans.create');
            exit;
        }
    }

    // Show loan detail
    public function show() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $loan = $this->loan->findByIdAndUser($id, $user_id);
        if (!$loan) {
            $_SESSION['error'] = 'وام مورد نظر یافت نشد.';
            header('Location: /?action=loans');
            exit;
        }

        $installments = $this->loanInstallment->getByLoanId($id);
        $stats = $this->loanInstallment->getPaymentStats($id);
        $next_due = $this->loanInstallment->getNextDueInstallment($id);

        // Calculate remaining balance
        $remaining_balance = $loan['principal'] - $stats['total_paid'];
        $remaining_installments = $stats['total_installments'] - $stats['paid_installments'];

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/loans/view.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Show edit form
    public function edit() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_GET['id'] ?? 0);

        $loan = $this->loan->findByIdAndUser($id, $user_id);
        if (!$loan) {
            $_SESSION['error'] = 'وام مورد نظر یافت نشد.';
            header('Location: /?action=loans');
            exit;
        }

        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/loans/edit.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }

    // Process update
    public function update() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);

        $lender_name = trim($_POST['lender_name'] ?? '');
        $principal = (int)($_POST['principal'] ?? 0);
        $interest = (float)($_POST['interest'] ?? 0);
        $start_date = $_POST['start_date'] ?? date('Y-m-d');
        $start_date = \App\Helpers\JalaliHelper::toGregorian($_POST['start_date'] ?? \App\Helpers\JalaliHelper::now());
        $term_months = (int)($_POST['term_months'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if (empty($lender_name)) {
            $_SESSION['error'] = 'لطفا نام وام‌دهنده را وارد کنید.';
            header("Location: /?action=loans.edit&id=$id");
            exit;
        }
        if ($principal <= 0) {
            $_SESSION['error'] = 'مبلغ اصلی باید بیشتر از صفر باشد.';
            header("Location: /?action=loans.edit&id=$id");
            exit;
        }
        if ($term_months <= 0) {
            $_SESSION['error'] = 'تعداد اقساط باید بیشتر از صفر باشد.';
            header("Location: /?action=loans.edit&id=$id");
            exit;
        }

        if ($this->loan->update($id, $user_id, $lender_name, $principal, $interest, $start_date, $term_months, $description)) {
            $_SESSION['success'] = 'وام با موفقیت به‌روزرسانی شد و اقساط جدید ایجاد گردید.';
            header("Location: /?action=loans.show&id=$id");
            exit;
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی وام.';
            header("Location: /?action=loans.edit&id=$id");
            exit;
        }
    }

    // Update status
    public function updateStatus() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['active', 'completed', 'cancelled'])) {
            $_SESSION['error'] = 'وضعیت نامعتبر است.';
            header('Location: /?action=loans');
            exit;
        }

        $loan = $this->loan->findByIdAndUser($id, $user_id);
        if (!$loan) {
            $_SESSION['error'] = 'وام مورد نظر یافت نشد.';
            header('Location: /?action=loans');
            exit;
        }

        if ($this->loan->updateStatus($id, $user_id, $status)) {
            $_SESSION['success'] = 'وضعیت وام با موفقیت به‌روزرسانی شد.';
        } else {
            $_SESSION['error'] = 'خطا در به‌روزرسانی وضعیت وام.';
        }
        header("Location: /?action=loans.show&id=$id");
        exit;
    }

    // Delete loan
    public function destroy() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($this->loan->delete($id, $user_id)) {
            $_SESSION['success'] = 'وام با موفقیت حذف شد.';
        } else {
            $_SESSION['error'] = 'خطا در حذف وام.';
        }
        header('Location: /?action=loans');
        exit;
    }

    // Mark installment as paid
    public function markInstallmentPaid() {
        AuthHelper::requireLogin();
        $user_id = AuthHelper::getCurrentUserId();
        $installment_id = (int)($_POST['installment_id'] ?? 0);
        $paid_date = $_POST['paid_date'] ?? date('Y-m-d');

        if ($installment_id <= 0) {
            $_SESSION['error'] = 'شناسه قسط نامعتبر است.';
            header('Location: /?action=loans');
            exit;
        }

        // Verify installment belongs to user's loan
        $query = "SELECT l.id FROM loan_installments li JOIN loans l ON li.loan_id = l.id WHERE li.id = :installment_id AND l.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':installment_id', $installment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $loan = $stmt->fetch();

        if (!$loan) {
            $_SESSION['error'] = 'قسط مورد نظر یافت نشد.';
            header('Location: /?action=loans');
            exit;
        }

        if ($this->loanInstallment->markAsPaid($installment_id, $user_id, $paid_date)) {
            $_SESSION['success'] = 'قسط با موفقیت به عنوان پرداخت شده علامت‌گذاری شد.';
        } else {
            $_SESSION['error'] = 'خطا در علامت‌گذاری قسط.';
        }
        header("Location: /?action=loans.show&id=" . $loan['id']);
        exit;
    }
}