<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>داشبورد کاربری</h3>
    <a href="/?action=transactions.create" class="btn btn-success">➕ افزودن تراکنش جدید</a>
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

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-bg-success mb-3">
            <div class="card-header">درآمدها</div>
            <div class="card-body">
                <h5 class="card-title"><?= number_format($total_income) ?> تومان</h5>
                <p class="card-text">مجموع درآمدهای شما در تمام حساب‌ها</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-bg-danger mb-3">
            <div class="card-header">هزینه‌ها</div>
            <div class="card-body">
                <h5 class="card-title"><?= number_format($total_expense) ?> تومان</h5>
                <p class="card-text">مجموع هزینه‌های شما در تمام حساب‌ها</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        نمودار درآمد و هزینه (۳۰ روز گذشته)
    </div>
    <div class="card-body">
        <canvas id="incomeExpenseChart" height="100"></canvas>
    </div>
</div>

<?php if (!empty($upcoming_reminders)): ?>
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        یادآوری‌های پیش‌رو (<?= count($upcoming_reminders) ?> مورد)
    </div>
    <ul class="list-group list-group-flush">
        <?php foreach ($upcoming_reminders as $reminder): ?>
        <li class="list-group-item">
            <strong><?= htmlspecialchars($reminder['title']) ?></strong><br>
            <small><?= htmlspecialchars($reminder['body']) ?></small><br>
            <span class="badge bg-secondary"><?= $reminder['created_at'] ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch real data from PHP
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    
    // Get real data from PHP (last 6 months)
    const labels = <?= json_encode($chart_labels) ?>;
    const incomeData = <?= json_encode($chart_income) ?>;
    const expenseData = <?= json_encode($chart_expense) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'درآمدها',
                data: incomeData,
                borderColor: 'green',
                backgroundColor: 'rgba(0, 255, 0, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'هزینه‌ها',
                data: expenseData,
                borderColor: 'red',
                backgroundColor: 'rgba(255, 0, 0, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('fa-IR').format(context.parsed.y) + ' تومان';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fa-IR').format(value) + ' ت';
                        }
                    }
                }
            }
        }
    });
});
</script>

<!-- Notification count is passed from controller -->

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <span>یادآوری‌های پیش‌رو (<?= $notification_count['unread'] ?> جدید)</span>
                <a href="/?action=notifications" class="btn btn-sm btn-outline-dark">مشاهده همه</a>
            </div>
            <?php if (!empty($upcoming_reminders)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($upcoming_reminders as $reminder): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong><?= htmlspecialchars($reminder['title']) ?></strong><br>
                            <small><?= htmlspecialchars($reminder['body']) ?></small><br>
                            <small class="text-muted"><?= $reminder['created_at'] ?></small>
                        </div>
                        <a href="/?action=notifications.markRead&id=<?= $reminder['id'] ?>" class="btn btn-sm btn-outline-secondary">علامت‌گذاری به عنوان خوانده</a>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <div class="card-body text-center">
                <p class="mb-0">هیچ یادآوری فعالی وجود ندارد.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent activities are passed from controller -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                فعالیت‌های اخیر
            </div>
            <?php if (!empty($recent_activities)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($recent_activities as $activity): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong><?= htmlspecialchars($activity['action']) ?></strong><br>
                            <small><?= htmlspecialchars($activity['context']) ?></small><br>
                            <small class="text-muted">تاریخ: <?= $activity['created_at'] ?> | کاربر: <?= htmlspecialchars($activity['user_name']) ?></small>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-footer text-center">
                <a href="/?action=audit" class="btn btn-sm btn-outline-info">مشاهده همه فعالیت‌ها</a>
            </div>
            <?php else: ?>
            <div class="card-body text-center">
                <p class="mb-0">هیچ فعالیت اخیری وجود ندارد.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>