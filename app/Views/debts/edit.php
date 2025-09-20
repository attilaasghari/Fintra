<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>ویرایش <?= $debt['type'] === 'debt' ? 'بدهی' : 'طلب' ?>: <?= htmlspecialchars($debt['person_name']) ?></h3>
    <a href="/?action=debts.show&id=<?= $debt['id'] ?>" class="btn btn-outline-secondary">بازگشت</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=debts.update" class="card p-4">
    <input type="hidden" name="id" value="<?= $debt['id'] ?>">

    <div class="mb-3">
        <label for="person_name" class="form-label">نام شخص <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="person_name" name="person_name" value="<?= htmlspecialchars($debt['person_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">نوع <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeDebt" value="debt" <?= $debt['type'] === 'debt' ? 'checked' : '' ?>>
            <label class="form-check-label" for="typeDebt">بدهی (بدهکاری)</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeCredit" value="credit" <?= $debt['type'] === 'credit' ? 'checked' : '' ?>>
            <label class="form-check-label" for="typeCredit">طلب (بستانکاری)</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">مبلغ (تومان) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="amount" name="amount" value="<?= $debt['amount'] ?>" min="1" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">شماره تلفن (اختیاری)</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($debt['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="due_date" class="form-label">تاریخ سررسید (اختیاری)</label>
        <input type="text" class="form-control" id="due_date" name="due_date" value="<?= $debt['due_date'] ? \App\Helpers\JalaliHelper::toJalali($debt['due_date']) : '' ?>" placeholder="1404/07/25">
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">توضیحات (اختیاری)</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($debt['description'] ?? '') ?></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-warning">به‌روزرسانی بدهی/طلب</button>
    </div>
</form>