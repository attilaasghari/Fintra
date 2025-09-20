<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ثبت نام - سامانه حسابداری شخصی</title>
    <link href="/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4>ثبت نام کاربر جدید</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/?action=register">
                        <div class="mb-3">
                            <label for="name" class="form-label">نام و نام خانوادگی</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">ایمیل</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">رمز عبور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">شماره تلفن (اختیاری)</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">ثبت نام</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/?action=login">قبلا ثبت نام کرده‌اید؟ وارد شوید</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
    <a href="/" class="text-decoration-none">← بازگشت به صفحه اصلی</a>
</div>
</div>
</body>
</html>