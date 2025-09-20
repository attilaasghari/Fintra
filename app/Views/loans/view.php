<?php
$installments = $this->loanInstallment->getByLoanId($loan['id']);
$stats = $this->loanInstallment->getPaymentStats($loan['id']);
$next_due = $this->loanInstallment->getNextDueInstallment($loan['id']);

// Calculate total payable amount (principal + interest)
$total_payable = $loan['installment_amount'] * $loan['term_months'];
$remaining_balance = $total_payable - $stats['total_paid']; // ✅ Now correct
$remaining_installments = $stats['total_installments'] - $stats['paid_installments'];

// Progress based on installments paid (not monetary value)
$progress_percent = $stats['total_installments'] > 0 ? round(($stats['paid_installments'] / $stats['total_installments']) * 100) : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>وام: <?= htmlspecialchars($loan['lender_name']) ?></h3>
    <div>
        <a href="/?action=loans.edit&id=<?= $loan['id'] ?>" class="btn btn-warning btn-sm">ویرایش</a>
        <a href="/?action=loans" class="btn btn-outline-secondary btn-sm">بازگشت</a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                اطلاعات وام
            </div>
            <div class="card-body">
                <p><strong>وام‌دهنده:</strong> <?= htmlspecialchars($loan['lender_name']) ?></p>
                <p><strong>مبلغ اصلی:</strong> <?= number_format($loan['principal']) ?> تومان</p>
                <p><strong>مجموع قابل پرداخت (اصل + سود):</strong> <?= number_format($total_payable) ?> تومان</p>
                <p><strong>کل سود قابل پرداخت:</strong> <?= number_format($total_payable - $loan['principal']) ?> تومان</p>
                <p><strong>نرخ سود سالانه:</strong> <?= $loan['interest'] ?>%</p>
                <p><strong>تعداد کل اقساط:</strong> <?= $stats['total_installments'] ?> ماه</p>
                <p><strong>مبلغ هر قسط:</strong> <?= number_format($loan['installment_amount']) ?> تومان</p>
                <p><strong>تاریخ شروع:</strong> <?= \App\Helpers\JalaliHelper::toJalali($loan['start_date']) ?></p>
                <?php if ($loan['description']): ?>
                    <p><strong>توضیحات:</strong> <?= htmlspecialchars($loan['description']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                وضعیت پرداخت
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress_percent ?>%" aria-valuenow="<?= $progress_percent ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $progress_percent ?>%
                        </div>
                    </div>
                </div>
                <p><strong>اقساط پرداخت شده:</strong> <?= $stats['paid_installments'] ?> از <?= $stats['total_installments'] ?></p>
                <p><strong>مبلغ پرداخت شده:</strong> <?= number_format($stats['total_paid']) ?> تومان</p>
                <p><strong>مانده حساب:</strong> <span class="fs-5 fw-bold text-danger"><?= number_format($remaining_balance) ?> تومان</span></p>
                <p><strong>اقساط باقیمانده:</strong> <?= $remaining_installments ?> قسط</p>
                <?php if ($next_due): ?>
                    <p><strong>بعدین قسط:</strong> <?= $next_due['due_date'] ?> (<?= number_format($next_due['amount']) ?> تومان)</p>
                <?php endif; ?>
                <p><strong>وضعیت فعلی:</strong> 
                    <?php
                    $status_badge = [
                        'active' => 'bg-primary',
                        'completed' => 'bg-success',
                        'cancelled' => 'bg-secondary'
                    ];
                    $status_text = [
                        'active' => 'فعال',
                        'completed' => 'تسویه شده',
                        'cancelled' => 'لغو شده'
                    ];
                    ?>
                    <span class="badge <?= $status_badge[$loan['status']] ?>">
                        <?= $status_text[$loan['status']] ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>تغییر وضعیت وام</span>
    </div>
    <div class="card-body">
        <form method="POST" action="/?action=loans.updateStatus" onsubmit="return confirm('آیا از تغییر وضعیت وام اطمینان دارید؟')">
            <input type="hidden" name="id" value="<?= $loan['id'] ?>">
            
            <div class="mb-3">
                <label for="status" class="form-label">وضعیت جدید</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?= $loan['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                    <option value="completed" <?= $loan['status'] === 'completed' ? 'selected' : '' ?>>تسویه شده</option>
                    <option value="cancelled" <?= $loan['status'] === 'cancelled' ? 'selected' : '' ?>>لغو شده</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning">به‌روزرسانی وضعیت</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>لیست اقساط</span>
    </div>
    <div class="card-body">
        <?php if (empty($installments)): ?>
            <div class="alert alert-info">هیچ قسطی برای این وام ثبت نشده است.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>شماره قسط</th>
                            <th>تاریخ سررسید</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>تاریخ پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($installments as $index => $i): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= \App\Helpers\JalaliHelper::toJalali($i['due_date']) ?></td>
                            <td><?= number_format($i['amount']) ?> تومان</td>
                            <td>
                                <?php
                                $status_badge = [
                                    'pending' => 'bg-warning',
                                    'paid' => 'bg-success'
                                ];
                                $status_text = [
                                    'pending' => 'در انتظار',
                                    'paid' => 'پرداخت شده'
                                ];
                                ?>
                                <span class="badge <?= $status_badge[$i['status']] ?>">
                                    <?= $status_text[$i['status']] ?>
                                </span>
                            </td>
                            <td><?= $i['paid_date'] ? \App\Helpers\JalaliHelper::toJalali($i['paid_date']) : '—' ?></td>
                            <td>
                                <?php if ($i['status'] === 'pending'): ?>
                                    <form method="POST" action="/?action=loans.markInstallmentPaid" onsubmit="return confirm('آیا از ثبت پرداخت این قسط اطمینان دارید؟')" class="d-inline">
                                        <input type="hidden" name="installment_id" value="<?= $i['id'] ?>">
                                        <input type="date" name="paid_date" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm d-inline" style="width: 150px;" required>
                                        <button type="submit" class="btn btn-sm btn-success">ثبت پرداخت</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>