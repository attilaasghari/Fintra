<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>تراکنش‌ها</h3>
    <a href="/?action=transactions.create" class="btn btn-primary">➕ افزودن تراکنش</a>
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
        فیلترها
    </div>
    <div class="card-body">
        <form method="GET" action="/?action=transactions" class="row g-3">
            <input type="hidden" name="action" value="transactions">
            
            <div class="col-md-3">
                <label for="account_id" class="form-label">حساب</label>
                <select class="form-select" id="account_id" name="account_id">
                    <option value="">همه حساب‌ها</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>" <?= (isset($_GET['account_id']) && $_GET['account_id'] == $acc['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($acc['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="category_id" class="form-label">دسته‌بندی</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">همه دسته‌ها</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label for="type" class="form-label">نوع</label>
                <select class="form-select" id="type" name="type">
                    <option value="">همه</option>
                    <option value="income" <?= (isset($_GET['type']) && $_GET['type'] == 'income') ? 'selected' : '' ?>>درآمد</option>
                    <option value="expense" <?= (isset($_GET['type']) && $_GET['type'] == 'expense') ? 'selected' : '' ?>>هزینه</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="start_date" class="form-label">از تاریخ</label>
                <input type="text" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? \App\Helpers\JalaliHelper::now()) ?>" placeholder="1404/07/25">
            </div>
                                
            <div class="col-md-2">
                <label for="end_date" class="form-label">تا تاریخ</label>
                <input type="text" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? \App\Helpers\JalaliHelper::now()) ?>" placeholder="1404/07/25">
            </div>

            <div class="col-md-4">
                <label for="search" class="form-label">جستجو</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="توضیحات یا نام حساب..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="/?action=transactions" class="btn btn-outline-secondary w-100">پاک کردن فیلترها</a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-bg-success mb-3">
            <div class="card-header">مجموع درآمدها</div>
            <div class="card-body">
                <h5 class="card-title"><?= number_format($total_income) ?> تومان</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-bg-danger mb-3">
            <div class="card-header">مجموع هزینه‌ها</div>
            <div class="card-body">
                <h5 class="card-title"><?= number_format($total_expense) ?> تومان</h5>
            </div>
        </div>
    </div>
</div>

<?php if (empty($transactions)): ?>
    <div class="alert alert-info">هیچ تراکنشی یافت نشد. <a href="/?action=transactions.create">اولین تراکنش خود را ثبت کنید</a>.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>تاریخ</th>
                    <th>حساب</th>
                    <th>دسته</th>
                    <th>نوع</th>
                    <th>مبلغ</th>
                    <th>توضیحات</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= \App\Helpers\JalaliHelper::toJalali($t['trans_date']) ?></td>
                    <td><?= htmlspecialchars($t['account_title']) ?></td>
                    <td>
                        <?php if ($t['category_name']): ?>
                            <span class="badge bg-secondary"><?= htmlspecialchars($t['category_name']) ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $t['type'] === 'income' ? 'success' : 'danger' ?>">
                            <?= $t['type'] === 'income' ? 'درآمد' : 'هزینه' ?>
                        </span>
                    </td>
                    <td><?= number_format($t['amount']) ?> تومان</td>
                    <td><?= htmlspecialchars($t['description'] ?? '—') ?></td>
                    <td>
                        <a href="/?action=transactions.edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary">ویرایش</a>
                        <a href="/?action=transactions.destroy&id=<?= $t['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('⚠️ آیا از حذف این تراکنش اطمینان دارید؟')">
                            حذف
                        </a>
                    </td>
                </tr>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal<?= $t['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="deleteModalLabel">تأیید حذف</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                آیا از حذف تراکنش «<strong><?= $t['type'] === 'income' ? 'درآمد' : 'هزینه' ?> <?= number_format($t['amount']) ?> تومانی</strong>» اطمینان دارید؟
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                <form method="POST" action="/?action=transactions.destroy" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-danger">حذف نهایی</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>