<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>ویرایش تراکنش</h3>
    <a href="/?action=transactions" class="btn btn-outline-secondary">بازگشت به لیست</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=transactions.update" class="card p-4">
    <input type="hidden" name="id" value="<?= $transaction['id'] ?>">

    <div class="mb-3">
        <label for="account_id" class="form-label">حساب <span class="text-danger">*</span></label>
        <select class="form-select" id="account_id" name="account_id" required>
            <option value="">انتخاب کنید...</option>
            <?php foreach ($accounts as $acc): ?>
                <option value="<?= $acc['id'] ?>" <?= ($acc['id'] == $transaction['account_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($acc['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">نوع تراکنش <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeIncome" value="income" <?= ($transaction['type'] === 'income') ? 'checked' : '' ?>>
            <label class="form-check-label" for="typeIncome">درآمد</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeExpense" value="expense" <?= ($transaction['type'] === 'expense') ? 'checked' : '' ?>>
            <label class="form-check-label" for="typeExpense">هزینه</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">مبلغ (تومان) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="amount" name="amount" value="<?= $transaction['amount'] ?>" min="1" required>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">دسته‌بندی (اختیاری)</label>
        <select class="form-select" id="category_id" name="category_id">
            <option value="">بدون دسته‌بندی</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $transaction['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="trans_date" class="form-label">تاریخ <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="trans_date" name="trans_date" value="<?= \App\Helpers\JalaliHelper::toJalali($transaction['trans_date']) ?>" placeholder="مثال: 1404/07/25" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">توضیحات (اختیاری)</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($transaction['description'] ?? '') ?></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-warning">به‌روزرسانی تراکنش</button>
    </div>
</form>