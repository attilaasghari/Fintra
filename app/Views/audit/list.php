<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>فعالیت‌های اخیر</h3>
    <div>
        <a href="/?action=audit.export&format=excel" class="btn btn-outline-primary btn-sm">خروجی Excel</a>
        <a href="/?action=audit.export&format=csv" class="btn btn-outline-secondary btn-sm">خروجی CSV</a>
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
        <form method="GET" action="/?action=audit" class="row g-3">
            <input type="hidden" name="action" value="audit">
            
            <div class="col-md-3">
                <label for="start_date" class="form-label">از تاریخ</label>
                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="1404/07/25">
            </div>

            <div class="col-md-3">
                <label for="end_date" class="form-label">تا تاریخ</label>
                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="1404/07/25">
            </div>

            <div class="col-md-3">
                <label for="end_date" class="form-label">تا تاریخ</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($logs)): ?>
    <div class="alert alert-info">هیچ فعالیتی ثبت نشده است.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>تاریخ</th>
                    <th>کاربر</th>
                    <th>عملیات</th>
                    <th>جزئیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= \App\Helpers\JalaliHelper::toJalali($log['created_at']) ?></td>
                    <td><?= htmlspecialchars($log['user_name']) ?></td>
                    <td>
                        <?php
                        $action_badge = '';
                        if (strpos($log['action'], 'create') !== false) {
                            $action_badge = 'bg-success';
                        } elseif (strpos($log['action'], 'update') !== false) {
                            $action_badge = 'bg-warning';
                        } elseif (strpos($log['action'], 'delete') !== false) {
                            $action_badge = 'bg-danger';
                        } else {
                            $action_badge = 'bg-info';
                        }
                        ?>
                        <span class="badge <?= $action_badge ?>"><?= htmlspecialchars($log['action']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($log['context']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>