<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>پشتیبان‌گیری و بازیابی</h3>
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

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                ایجاد پشتیبان‌گیری جدید
            </div>
            <div class="card-body">
                <p>با کلیک روی دکمه زیر، یک نسخه کامل از پایگاه داده ایجاد خواهد شد.</p>
                <p><strong>هشدار:</strong> این فایل شامل تمام داده‌های شماست. آن را در مکانی امن نگهداری کنید.</p>
                <form method="POST" action="/?action=backup.create" onsubmit="return confirm('آیا از ایجاد پشتیبان‌گیری جدید اطمینان دارید؟')">
                    <button type="submit" class="btn btn-success w-100">ایجاد پشتیبان‌گیری جدید</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                بازیابی از پشتیبان‌گیری
            </div>
            <div class="card-body">
                <p>با بازیابی از یک فایل پشتیبان، تمام داده‌های فعلی جایگزین خواهند شد.</p>
                <p class="text-danger"><strong>هشدار:</strong> این عملیات غیرقابل بازگشت است!</p>
                <form method="POST" action="/?action=backup.restore" onsubmit="return confirm('⚠️ هشدار: این عملیات تمام داده‌های فعلی را با داده‌های فایل پشتیبان جایگزین می‌کند. آیا از ادامه اطمینان دارید؟')">
                    <div class="mb-3">
                        <label for="filename" class="form-label">فایل پشتیبان</label>
                        <select class="form-select" id="filename" name="filename" required>
                            <option value="">انتخاب کنید...</option>
                            <?php foreach ($backup_files as $file): ?>
                                <option value="<?= htmlspecialchars($file['filename']) ?>"><?= htmlspecialchars($file['filename']) ?> (<?= round($file['size'] / 1024, 2) ?> KB)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="confirm" name="confirm" required>
                        <label class="form-check-label" for="confirm">من تأیید می‌کنم که از این عملیات اطمینان دارم و مسئولیت از دست رفتن داده‌های فعلی را می‌پذیرم.</label>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">بازیابی از پشتیبان‌گیری</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($backup_files)): ?>
    <div class="card">
        <div class="card-header">
            فایل‌های پشتیبان موجود
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>نام فایل</th>
                            <th>اندازه</th>
                            <th>تاریخ ایجاد</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backup_files as $file): ?>
                        <tr>
                            <td><?= htmlspecialchars($file['filename']) ?></td>
                            <td><?= round($file['size'] / 1024, 2) ?> KB</td>
                            <td><?= date('Y-m-d H:i:s', $file['modified']) ?></td>
                            <td>
                                <a href="/?action=backup.download&filename=<?= urlencode($file['filename']) ?>" class="btn btn-sm btn-outline-primary">دانلود</a>
                                <a href="/?action=backup.delete&filename=<?= urlencode($file['filename']) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('آیا از حذف این فایل پشتیبان اطمینان دارید؟')">
                                    حذف
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">هیچ فایل پشتیبانی وجود ندارد. ابتدا یک پشتیبان‌گیری ایجاد کنید.</div>
<?php endif; ?>