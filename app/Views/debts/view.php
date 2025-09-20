<?php
$payments = $this->debtPayment->getByDebtId($debt['id']);
$total_paid = $this->debtPayment->getTotalPaid($debt['id']);
$remaining = $debt['amount'] - $total_paid;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>
        <?= $debt['type'] === 'debt' ? 'بدهی' : 'طلب' ?>: 
        <?= htmlspecialchars($debt['person_name']) ?>
    </h3>
    <div>
        <a href="/?action=debts.edit&id=<?= $debt['id'] ?>" class="btn btn-warning btn-sm">ویرایش</a>
        <a href="/?action=debts" class="btn btn-outline-secondary btn-sm">بازگشت</a>
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
            <div class="card-header bg-<?= $debt['type'] === 'debt' ? 'danger' : 'success' ?> text-white">
                اطلاعات <?= $debt['type'] === 'debt' ? 'بدهی' : 'طلب' ?>
            </div>
            <div class="card-body">
                <p><strong>نام شخص:</strong> <?= htmlspecialchars($debt['person_name']) ?></p>
                <?php if ($debt['phone']): ?>
                    <p><strong>شماره تلفن:</strong> <?= htmlspecialchars($debt['phone']) ?></p>
                <?php endif; ?>
                <p><strong>مبلغ کل:</strong> <?= number_format($debt['amount']) ?> تومان</p>
                <p><strong>پرداخت شده:</strong> <?= number_format($total_paid) ?> تومان</p>
                <p><strong>مانده:</strong> <span class="fs-5 fw-bold text-<?= $remaining > 0 ? 'danger' : 'success' ?>"><?= number_format($remaining) ?> تومان</span></p>
                <p><strong>وضعیت:</strong> 
                    <?php
                    $status_badge = [
                        'unpaid' => 'bg-secondary',
                        'partial' => 'bg-warning',
                        'paid' => 'bg-success'
                    ];
                    $status_text = [
                        'unpaid' => 'پرداخت نشده',
                        'partial' => 'پرداخت جزئی',
                        'paid' => 'پرداخت شده'
                    ];
                    ?>
                    <span class="badge <?= $status_badge[$debt['status']] ?>">
                        <?= $status_text[$debt['status']] ?>
                    </span>
                </p>
                <!-- Due date -->
                <?php if ($debt['due_date']): ?>
                    <p><strong>تاریخ سررسید:</strong> <?= \App\Helpers\JalaliHelper::toJalali($debt['due_date']) ?>
                        <?php if (strtotime($debt['due_date']) < strtotime(date('Y-m-d')) && $debt['status'] !== 'paid'): ?>
                            <span class="badge bg-danger">منقضی شده</span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                <?php if ($debt['description']): ?>
                    <p><strong>توضیحات:</strong> <?= htmlspecialchars($debt['description']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                ثبت پرداخت جدید
            </div>
            <div class="card-body">
                <form method="POST" action="/?action=debts.recordPayment" onsubmit="return confirm('آیا از ثبت این پرداخت اطمینان دارید؟')">
                    <input type="hidden" name="debt_id" value="<?= $debt['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">مبلغ پرداختی (تومان) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" min="1" max="<?= $remaining ?>" required>
                        <small class="form-text text-muted">مانده قابل پرداخت: <?= number_format($remaining) ?> تومان</small>
                    </div>

                    <div class="mb-3">
                        <label for="payment_date" class="form-label">تاریخ پرداخت <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">یادداشت (اختیاری)</label>
                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">ثبت پرداخت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>تغییر وضعیت</span>
    </div>
    <div class="card-body">
        <form method="POST" action="/?action=debts.updateStatus" onsubmit="return confirm('آیا از تغییر وضعیت اطمینان دارید؟ این عملیات نیاز به تأیید دارد.')">
            <input type="hidden" name="id" value="<?= $debt['id'] ?>">
            
            <div class="mb-3">
                <label for="status" class="form-label">وضعیت جدید</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="unpaid" <?= $debt['status'] === 'unpaid' ? 'selected' : '' ?>>پرداخت نشده</option>
                    <option value="partial" <?= $debt['status'] === 'partial' ? 'selected' : '' ?>>پرداخت جزئی</option>
                    <option value="paid" <?= $debt['status'] === 'paid' ? 'selected' : '' ?> <?= $remaining > 0 ? 'disabled' : '' ?>>پرداخت شده</option>
                </select>
                <?php if ($remaining > 0): ?>
                    <small class="form-text text-danger">برای تغییر به "پرداخت شده"، باید کل مبلغ پرداخت شود.</small>
                <?php endif; ?>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning">به‌روزرسانی وضعیت</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($payments)): ?>
<div class="card">
    <div class="card-header">
        تاریخچه پرداخت‌ها
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>تاریخ پرداخت</th>
                        <th>مبلغ</th>
                        <th>یادداشت</th>
                        <th>تاریخ ثبت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= \App\Helpers\JalaliHelper::toJalali($p['payment_date']) ?></td>
                        <td><?= number_format($p['amount']) ?> تومان</td>
                        <td><?= htmlspecialchars($p['note'] ?? '—') ?></td>
                        <small class="text-muted">تاریخ: <?= \App\Helpers\JalaliHelper::toJalali($p['created_at']) ?></small>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>