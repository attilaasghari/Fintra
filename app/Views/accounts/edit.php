<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>ویرایش حساب: <?= htmlspecialchars($account['title']) ?></h3>
    <a href="/?action=accounts.show&id=<?= $account['id'] ?>" class="btn btn-outline-secondary">بازگشت</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=accounts.update" class="card p-4">
    <input type="hidden" name="id" value="<?= $account['id'] ?>">

    <div class="mb-3">
        <label for="title" class="form-label">عنوان حساب <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($account['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">دسته‌بندی (اختیاری)</label>
        <select class="form-select" id="category_id" name="category_id">
            <option value="">بدون دسته‌بندی</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $account['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="account_number" class="form-label">شماره حساب (اختیاری)</label>
        <input type="text" class="form-control" id="account_number" name="account_number" value="<?= htmlspecialchars($account['account_number'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="card_number" class="form-label">شماره کارت (اختیاری)</label>
        <input type="text" class="form-control" id="card_number" name="card_number" value="<?= htmlspecialchars($account['card_number'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="initial_balance" class="form-label">مانده اولیه (تومان)</label>
        <input type="number" class="form-control" id="initial_balance" name="initial_balance" value="<?= $account['initial_balance'] ?>" min="0">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-warning">به‌روزرسانی حساب</button>
    </div>
</form>