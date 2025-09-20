<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>مرکز اعلان‌ها</h3>
    <div>
        <a href="/?action=notifications.markAllRead" class="btn btn-outline-primary btn-sm">علامت‌گذاری همه به عنوان خوانده</a>
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

<div class="card mb-4">
    <div class="card-header">
        فیلترها
    </div>
    <div class="card-body">
        <form method="GET" action="/?action=notifications" class="row g-3">
            <input type="hidden" name="action" value="notifications">
            
            <div class="col-md-3">
                <label for="type" class="form-label">نوع</label>
                <select class="form-select" id="type" name="type">
                    <option value="">همه</option>
                    <option value="loan_installment" <?= (isset($_GET['type']) && $_GET['type'] == 'loan_installment') ? 'selected' : '' ?>>قسط وام</option>
                    <option value="debt_due" <?= (isset($_GET['type']) && $_GET['type'] == 'debt_due') ? 'selected' : '' ?>>سررسید بدهی/طلب</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="is_read" class="form-label">وضعیت</label>
                <select class="form-select" id="is_read" name="is_read">
                    <option value="">همه</option>
                    <option value="0" <?= (isset($_GET['is_read']) && $_GET['is_read'] == '0') ? 'selected' : '' ?>>خوانده نشده</option>
                    <option value="1" <?= (isset($_GET['is_read']) && $_GET['is_read'] == '1') ? 'selected' : '' ?>>خوانده شده</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="/?action=notifications" class="btn btn-outline-secondary w-100">پاک کردن فیلترها</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($notifications)): ?>
    <div class="alert alert-info">هیچ اعلانی وجود ندارد.</div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($notifications as $n): ?>
        <div class="list-group-item <?= $n['is_read'] ? 'list-group-item-light' : '' ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1"><?= htmlspecialchars($n['title']) ?></h6>
                    <p class="mb-1 small"><?= htmlspecialchars($n['body']) ?></p>
                    <small class="text-muted">تاریخ: <?= \App\Helpers\JalaliHelper::toJalali($n['created_at']) ?> | نوع: <?= $n['type'] ?></small>
                </div>
                <div class="d-flex flex-column gap-2">
                    <?php if (!$n['is_read']): ?>
                        <a href="/?action=notifications.markRead&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary">علامت‌گذاری به عنوان خوانده</a>
                    <?php endif; ?>
                    <a href="/?action=notifications.destroy&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('آیا از حذف این اعلان اطمینان دارید؟')">حذف</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>