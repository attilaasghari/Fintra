<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>حساب‌های من</h3>
    <a href="/?action=accounts.create" class="btn btn-primary">➕ ایجاد حساب جدید</a>
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
    <?php if (empty($accounts)): ?>
        <div class="col-12">
            <div class="alert alert-info">هیچ حسابی ایجاد نشده است. <a href="/?action=accounts.create">اولین حساب خود را بسازید</a>.</div>
        </div>
    <?php else: ?>
        <?php foreach ($accounts as $acc): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-<?= $acc['balance'] >= 0 ? 'success' : 'danger' ?> text-white">
                    <?= htmlspecialchars($acc['title']) ?>
                    <?php if ($acc['category_name']): ?>
                        <span class="badge bg-light text-dark float-end"><?= htmlspecialchars($acc['category_name']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($acc['account_number']): ?>
                        <p class="card-text"><small>شماره حساب: <?= htmlspecialchars($acc['account_number']) ?></small></p>
                    <?php endif; ?>
                    <?php if ($acc['card_number']): ?>
                        <p class="card-text"><small>شماره کارت: <?= htmlspecialchars($acc['card_number']) ?></small></p>
                    <?php endif; ?>
                    <h4 class="card-title"><?= number_format($acc['balance']) ?> تومان</h4>
                    <p class="card-text">مانده حساب</p>
                </div>
                <div class="card-footer text-center">
                    <a href="/?action=accounts.show&id=<?= $acc['id'] ?>" class="btn btn-sm btn-outline-primary">مشاهده جزئیات</a>
                    <a href="/?action=accounts.edit&id=<?= $acc['id'] ?>" class="btn btn-sm btn-outline-warning">ویرایش</a>
                    <a href="/?action=accounts.destroy&id=<?= $acc['id'] ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('⚠️ آیا از حذف این حساب و تمام تراکنش‌های آن اطمینان دارید؟')">
                        حذف
                    </a>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal<?= $acc['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">تأیید حذف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        آیا از حذف حساب «<strong><?= htmlspecialchars($acc['title']) ?></strong>» اطمینان دارید؟<br>
                        <small class="text-danger">تمام تراکنش‌های مرتبط نیز حذف خواهند شد.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <form method="POST" action="/?action=accounts.destroy" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $acc['id'] ?>">
                            <button type="submit" class="btn btn-danger">حذف نهایی</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>