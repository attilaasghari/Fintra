<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه حسابداری شخصی</title>
    <link href="/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/persian-datepicker.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js will be added later -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="https://fintra.vitren.ir">fintra حسابداری شخصی </a>
            <div class="d-flex">
                <span class="navbar-text text-white mx-3">
                    خوش آمدید، <?= \App\Helpers\AuthHelper::getCurrentUserName(); ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="/?action=logout">خروج</a>
            </div>
        </div>
        <div class="navbar-text text-white mx-3 d-none d-md-block">
            <i class="fas fa-calendar-day me-2"></i>
            <span id="current-date">
                <?php 
                if (class_exists('App\Helpers\JalaliHelper')) {
                    echo \App\Helpers\JalaliHelper::now() . ' (' . \App\Helpers\JalaliHelper::getDayName(date('Y-m-d')) . ')';
                } else {
                    echo date('Y/m/d');
                }
                ?>
            </span>
        </div>
    </nav>
    <div class="container-fluid mt-4">
        <div class="row">