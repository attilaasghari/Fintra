<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>گزارش‌ها و خروجی‌ها</h3>
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
        تولید گزارش جامع
    </div>
    <div class="card-body">
        <form method="POST" action="/?action=reports.generate" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">از تاریخ</label>
                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="1404/07/25">
            </div>
            
            <div class="col-md-3">
                <label for="end_date" class="form-label">تا تاریخ</label>
                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="1404/07/25">
            </div>

            <div class="col-md-3">
                <label for="account_ids" class="form-label">حساب‌ها</label>
                <select class="form-select" id="account_ids" name="account_ids[]" multiple>
                    <option value="">همه حساب‌ها</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['title']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">برای انتخاب چندگانه، Ctrl را نگه دارید</small>
            </div>

            <div class="col-md-3">
                <label for="category_ids" class="form-label">دسته‌بندی‌ها</label>
                <select class="form-select" id="category_ids" name="category_ids[]" multiple>
                    <option value="">همه دسته‌ها</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">برای انتخاب چندگانه، Ctrl را نگه دارید</small>
            </div>

            <div class="col-md-3">
                <label for="report_type" class="form-label">نوع گزارش</label>
                <select class="form-select" id="report_type" name="report_type">
                    <option value="summary">خلاصه و جزئیات</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="format" class="form-label">فرمت خروجی</label>
                <select class="form-select" id="format" name="format">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">تولید گزارش</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                خروجی تراکنش‌ها
            </div>
            <div class="card-body">
                <p>خروجی لیست تراکنش‌ها با فیلترهای تاریخ، حساب و دسته‌بندی</p>
                <a href="/?action=reports.exportTransactions" class="btn btn-outline-primary">دانلود Excel</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-danger text-white">
                خروجی بدهی‌ها و طلب‌ها
            </div>
            <div class="card-body">
                <p>خروجی لیست کامل بدهی‌ها و طلب‌ها با جزئیات</p>
                <a href="/?action=reports.exportDebts" class="btn btn-outline-danger">دانلود Excel</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                خروجی وام‌ها و اقساط
            </div>
            <div class="card-body">
                <p>خروجی لیست کامل وام‌ها و جزئیات اقساط</p>
                <a href="/?action=reports.exportLoans" class="btn btn-outline-success">دانلود Excel</a>
            </div>
        </div>
    </div>
</div>