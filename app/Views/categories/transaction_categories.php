<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>دسته‌بندی‌های تراکنش</h3>
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
        ایجاد دسته‌بندی جدید
    </div>
    <div class="card-body">
        <form method="POST" action="/?action=categories.transaction.create" class="row g-3">
            <div class="col-md-10">
                <input type="text" class="form-control" name="name" placeholder="مثال: حقوق، اجاره، غذا، حمل و نقل" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">افزودن</button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-info">هیچ دسته‌بندی تراکنشی ایجاد نشده است.</div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            لیست دسته‌بندی‌های تراکنش
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>نام دسته‌بندی</th>
                            <th>تعداد تراکنش‌های مرتبط</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><?= $cat['usage_count'] ?></td>
                            <td>
                                <!-- Edit Form -->
                                <form method="POST" action="/?action=categories.transaction.edit" class="d-inline" onsubmit="return confirm('آیا از به‌روزرسانی نام دسته‌بندی اطمینان دارید؟')">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                    <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" style="width: 120px; display: inline;" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">ذخیره</button>
                                </form>
                                
                                <!-- Delete Button -->
                                <?php if ($cat['usage_count'] == 0): ?>
                                    <a href="/?action=categories.transaction.delete&id=<?= $cat['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger ms-2"
                                       onclick="return confirm('⚠️ هشدار: آیا از حذف این دسته‌بندی اطمینان دارید؟')">
                                        حذف
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" disabled>
                                        حذف (در حال استفاده)
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>