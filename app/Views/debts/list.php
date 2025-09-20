<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>بدهی‌ها و طلب‌ها</h3>
    <a href="/?action=debts.create" class="btn btn-primary">➕ افزودن بدهی/طلب جدید</a>
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
            <div class="card-header bg-danger text-white">خلاصه بدهی‌ها</div>
            <div class="card-body">
                <p><strong>بدهی‌های پرداخت نشده:</strong> <?= number_format($summary['total_debt_unpaid']) ?> تومان</p>
                <p><strong>بدهی‌های پرداخت جزئی:</strong> <?= number_format($summary['total_debt_partial']) ?> تومان</p>
                <p><strong>بدهی‌های تسویه شده:</strong> <?= number_format($summary['total_debt_paid']) ?> تومان</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">خلاصه طلب‌ها</div>
            <div class="card-body">
                <p><strong>طلب‌های دریافت نشده:</strong> <?= number_format($summary['total_credit_unpaid']) ?> تومان</p>
                <p><strong>طلب‌های دریافت جزئی:</strong> <?= number_format($summary['total_credit_partial']) ?> تومان</p>
                <p><strong>طلب‌های تسویه شده:</strong> <?= number_format($summary['total_credit_paid']) ?> تومان</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        فیلترها
    </div>
    <div class="card-body">
        <form method="GET" action="/?action=debts" class="row g-3">
            <input type="hidden" name="action" value="debts">
            
            <div class="col-md-3">
                <label for="type" class="form-label">نوع</label>
                <select class="form-select" id="type" name="type">
                    <option value="">همه</option>
                    <option value="debt" <?= (isset($_GET['type']) && $_GET['type'] == 'debt') ? 'selected' : '' ?>>بدهی (بدهکاری)</option>
                    <option value="credit" <?= (isset($_GET['type']) && $_GET['type'] == 'credit') ? 'selected' : '' ?>>طلب (بستانکاری)</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label">وضعیت</label>
                <select class="form-select" id="status" name="status">
                    <option value="">همه</option>
                    <option value="unpaid" <?= (isset($_GET['status']) && $_GET['status'] == 'unpaid') ? 'selected' : '' ?>>پرداخت نشده</option>
                    <option value="partial" <?= (isset($_GET['status']) && $_GET['status'] == 'partial') ? 'selected' : '' ?>>پرداخت جزئی</option>
                    <option value="paid" <?= (isset($_GET['status']) && $_GET['status'] == 'paid') ? 'selected' : '' ?>>پرداخت شده</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="search" class="form-label">جستجو</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="نام شخص یا توضیحات..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($debts)): ?>
    <div class="alert alert-info">هیچ بدهی یا طلبی ثبت نشده است. <a href="/?action=debts.create">اولین مورد را ثبت کنید</a>.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>نام شخص</th>
                    <th>نوع</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>تاریخ سررسید</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($debts as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['person_name']) ?></td>
                    <td>
                        <span class="badge bg-<?= $d['type'] === 'debt' ? 'danger' : 'success' ?>">
                            <?= $d['type'] === 'debt' ? 'بدهی' : 'طلب' ?>
                        </span>
                    </td>
                    <td><?= number_format($d['amount']) ?> تومان</td>
                    <td>
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
                        <span class="badge <?= $status_badge[$d['status']] ?>">
                            <?= $status_text[$d['status']] ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($d['due_date']): ?>
                            <?= \App\Helpers\JalaliHelper::toJalali($d['due_date']) ?>
                            <?php if (strtotime($d['due_date']) < strtotime(date('Y-m-d')) && $d['status'] !== 'paid'): ?>
                                <span class="badge bg-danger">منقضی شده</span>
                            <?php endif; ?>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/?action=debts.show&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-info">مشاهده</a>
                        <a href="/?action=debts.edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary">ویرایش</a>
                        <a href="/?action=debts.destroy&id=<?= $d['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('آیا از حذف این بدهی/طلب اطمینان دارید؟')">
                            حذف
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>