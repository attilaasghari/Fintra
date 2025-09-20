<?php
// Ensure user_id is defined
if (!isset($user_id)) {
    $user_id = AuthHelper::getCurrentUserId();
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>ایجاد حساب جدید</h3>
    <a href="/?action=accounts" class="btn btn-outline-secondary">بازگشت به لیست</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=accounts.store" class="card p-4">
    <div class="mb-3">
        <label for="title" class="form-label">عنوان حساب <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" required>
        <small class="form-text text-muted">مثال: حساب ملی - شعبه تهران</small>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">دسته‌بندی (اختیاری)</label>
        <select class="form-select" id="category_id" name="category_id">
            <option value="">بدون دسته‌بندی</option>
            <?php foreach ($categories as $cat): ?>
                <?php if ($cat['user_id'] == $user_id): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="account_number" class="form-label">شماره حساب (اختیاری)</label>
        <input type="text" class="form-control" id="account_number" name="account_number">
    </div>

    <div class="mb-3">
        <label for="card_number" class="form-label">شماره کارت (اختیاری)</label>
        <input type="text" class="form-control" id="card_number" name="card_number">
    </div>

    <div class="mb-3">
        <label for="initial_balance" class="form-label">مانده اولیه (تومان)</label>
        <input type="number" class="form-control" id="initial_balance" name="initial_balance" value="0" min="0">
        <small class="form-text text-muted">اگر حساب شما موجودی اولیه دارد، مقدار آن را وارد کنید.</small>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">ایجاد حساب</button>
    </div>
</form>