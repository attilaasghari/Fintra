

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>جزئیات حساب: <?= htmlspecialchars($account['title']) ?></h3>
    <div>
        <a href="/?action=accounts.edit&id=<?= $account['id'] ?>" class="btn btn-warning btn-sm">ویرایش</a>
        <a href="/?action=accounts" class="btn btn-outline-secondary btn-sm">بازگشت</a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                اطلاعات حساب
            </div>
            <div class="card-body">
                <p><strong>عنوان:</strong> <?= htmlspecialchars($account['title']) ?></p>
                <?php if ($account['category_name']): ?>
                    <p><strong>دسته:</strong> <?= htmlspecialchars($account['category_name']) ?></p>
                <?php endif; ?>
                <?php if ($account['account_number']): ?>
                    <p><strong>شماره حساب:</strong> <?= htmlspecialchars($account['account_number']) ?></p>
                <?php endif; ?>
                <?php if ($account['card_number']): ?>
                    <p><strong>شماره کارت:</strong> <?= htmlspecialchars($account['card_number']) ?></p>
                <?php endif; ?>
                <p><strong>مانده اولیه:</strong> <?= number_format($account['initial_balance']) ?> تومان</p>
                <p><strong>مانده فعلی:</strong> <span class="fs-4 fw-bold text-<?= $balance >= 0 ? 'success' : 'danger' ?>"><?= number_format($balance) ?> تومان</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                خلاصه تراکنش‌ها
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>درآمدها:</span>
                    <strong class="text-success"><?= number_format($total_income) ?> تومان</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>هزینه‌ها:</span>
                    <strong class="text-danger"><?= number_format($total_expense) ?> تومان</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>مانده نهایی:</span>
                    <strong class="fs-5 <?= $balance >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($balance) ?> تومان</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        نمودار تراکنش‌ها (۳۰ روز گذشته)
    </div>
    <div class="card-body">
        <canvas id="accountChart" height="100"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>لیست تراکنش‌ها</span>
        <a href="/?action=transactions.create&account_id=<?= $account['id'] ?>" class="btn btn-sm btn-success">➕ تراکنش جدید</a>
    </div>
    <div class="card-body">
        <?php if (empty($transactions)): ?>
            <div class="alert alert-info">هیچ تراکنشی برای این حساب ثبت نشده است.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>نوع</th>
                            <th>مبلغ</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['trans_date']) ?></td>
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
                        <div class="modal fade" id="deleteModal<?= $t['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $t['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel<?= $t['id'] ?>">تأیید حذف</h5>
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('accountChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['هفته ۱', 'هفته ۲', 'هفته ۳', 'هفته ۴'],
            datasets: [{
                label: 'درآمدها',
                data: [<?= $total_income > 0 ? $total_income/4 : 0 ?>, <?= $total_income > 0 ? $total_income/4 : 0 ?>, <?= $total_income > 0 ? $total_income/4 : 0 ?>, <?= $total_income > 0 ? $total_income/4 : 0 ?>],
                borderColor: 'green',
                tension: 0.3
            }, {
                label: 'هزینه‌ها',
                data: [<?= $total_expense > 0 ? $total_expense/4 : 0 ?>, <?= $total_expense > 0 ? $total_expense/4 : 0 ?>, <?= $total_expense > 0 ? $total_expense/4 : 0 ?>, <?= $total_expense > 0 ? $total_expense/4 : 0 ?>],
                borderColor: 'red',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>