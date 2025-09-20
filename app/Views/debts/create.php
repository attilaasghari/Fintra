<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>افزودن بدهی/طلب جدید</h3>
    <a href="/?action=debts" class="btn btn-outline-secondary">بازگشت به لیست</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=debts.store" class="card p-4">
    <div class="mb-3">
        <label for="person_name" class="form-label">نام شخص <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="person_name" name="person_name" required>
    </div>

    <div class="mb-3">
        <label class="form-label">نوع <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeDebt" value="debt" checked>
            <label class="form-check-label" for="typeDebt">بدهی (بدهکاری)</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="typeCredit" value="credit">
            <label class="form-check-label" for="typeCredit">طلب (بستانکاری)</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">مبلغ (تومان) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="amount" name="amount" min="1" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">شماره تلفن (اختیاری)</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>

    <div class="mb-3">
    <label for="due_date" class="form-label">تاریخ سررسید (اختیاری)</label>
    <input type="text" class="form-control jalali-date-input" id="due_date" name="due_date" placeholder="1404-07-25">
    <small class="form-text text-muted">فرمت تاریخ: YYYY-MM-DD (مثال: 1404-07-25)</small>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">توضیحات (اختیاری)</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-success">ثبت بدهی/طلب</button>
    </div>
</form>