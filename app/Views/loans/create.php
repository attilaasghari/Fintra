<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>افزودن وام جدید</h3>
    <a href="/?action=loans" class="btn btn-outline-secondary">بازگشت به لیست</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="/?action=loans.store" class="card p-4">
    <div class="mb-3">
        <label for="lender_name" class="form-label">نام وام‌دهنده <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="lender_name" name="lender_name" required>
    </div>

    <div class="mb-3">
        <label for="principal" class="form-label">مبلغ اصلی وام (تومان) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="principal" name="principal" min="100000" step="10000" required>
    </div>

    <div class="mb-3">
        <label for="interest" class="form-label">نرخ سود سالانه (%) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="interest" name="interest" min="0" max="100" step="0.1" value="0" required>
        <small class="form-text text-muted">برای وام بدون سود، مقدار 0 را وارد کنید.</small>
    </div>

    <div class="mb-3">
        <label for="start_date" class="form-label">تاریخ شروع <span class="text-danger">*</span></label>
    <input type="text" class="form-control jalali-date-input" id="start_date" name="start_date" value="<?= \App\Helpers\JalaliHelper::now() ?>" placeholder="1404-07-25" required>
    </div>

    <div class="mb-3">
        <label for="term_months" class="form-label">تعداد اقساط (ماه) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="term_months" name="term_months" min="1" max="360" value="12" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">توضیحات (اختیاری)</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-success">ثبت وام و ایجاد اقساط</button>
    </div>
</form>