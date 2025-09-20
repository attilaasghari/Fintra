<?php http_response_code(500); ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطا در سرور - سامانه حسابداری شخصی</title>
    <link href="/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .error-container { margin: 100px auto; max-width: 600px; text-align: center; }
        .error-code { font-size: 6rem; font-weight: bold; color: #dc3545; }
        .error-message { font-size: 1.5rem; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">خطا در سرور</div>
        <p>متاسفیم! خطایی در سرور رخ داده است.</p>
        <a href="/" class="btn btn-primary mt-4">بازگشت به صفحه اصلی</a>
    </div>
</body>
</html>