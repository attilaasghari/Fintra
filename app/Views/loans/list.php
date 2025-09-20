<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>وام‌ها و اقساط</h3>
    <a href="/?action=loans.create" class="btn btn-primary">➕ افزودن وام جدید</a>
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
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-header">وام‌های فعال</div>
            <div class="card-body">
                <h5 class="card-title"><?= $summary_data['active_count'] ?> وام</h5>
                <p class="card-text"><?= number_format($summary_data['active_total']) ?> تومان</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-header">وام‌های تسویه شده</div>
            <div class="card-body">
                <h5 class="card-title"><?= $summary_data['completed_count'] ?> وام</h5>
                <p class="card-text"><?= number_format($summary_data['completed_total']) ?> تومان</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-secondary">
            <div class="card-header">وام‌های لغو شده</div>
            <div class="card-body">
                <h5 class="card-title"><?= $summary_data['cancelled_count'] ?> وام</h5>
                <p class="card-text"><?= number_format($summary_data['cancelled_total']) ?> تومان</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        فیلترها
    </div>
    <div class="card-body">
        <form method="GET" action="/?action=loans" class="row g-3">
            <input type="hidden" name="action" value="loans">
            
            <div class="col-md-3">
                <label for="status" class="form-label">وضعیت</label>
                <select class="form-select" id="status" name="status">
                    <option value="">همه</option>
                    <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>فعال</option>
                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>تسویه شده</option>
                    <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>لغو شده</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="search" class="form-label">جستجو</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="نام وام‌دهنده یا توضیحات..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($loans)): ?>
    <div class="alert alert-info">هیچ وامی ثبت نشده است. <a href="/?action=loans.create">اولین وام را ثبت کنید</a>.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>وام‌دهنده</th>
                    <th>مبلغ اصلی</th>
                    <th>تعداد اقساط</th>
                    <th>قسط ماهانه</th>
                    <th>وضعیت</th>
                    <th>تاریخ شروع</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['lender_name']) ?></td>
                    <td><?= number_format($l['principal']) ?> تومان</td>
                    <td><?= $l['term_months'] ?> ماه</td>
                    <td><?= number_format($l['installment_amount']) ?> تومان</td>
                    <td>
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
                        <span class="badge <?= $status_badge[$l['status']] ?>">
                            <?= $status_text[$l['status']] ?>
                        </span>
                    </td>
                    <td><?= $l['start_date'] ?></td>
                    <td>
                        <a href="/?action=loans.show&id=<?= $l['id'] ?>" class="btn btn-sm btn-outline-info">مشاهده</a>
                        <a href="/?action=loans.edit&id=<?= $l['id'] ?>" class="btn btn-sm btn-outline-primary">ویرایش</a>
                        <a href="/?action=loans.destroy&id=<?= $l['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('آیا از حذف این وام و تمام اقساط آن اطمینان دارید؟')">
                            حذف
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>