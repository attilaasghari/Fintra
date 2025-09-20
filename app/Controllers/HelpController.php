<?php
// app/Controllers/HelpController.php

namespace App\Controllers;

use App\Helpers\AuthHelper;

class HelpController {
    public function index() {
        AuthHelper::requireLogin();
        
        include PROJECT_ROOT . '/app/Views/layouts/header.php';
        include PROJECT_ROOT . '/app/Views/layouts/sidebar.php';
        include PROJECT_ROOT . '/app/Views/help/index.php';
        include PROJECT_ROOT . '/app/Views/layouts/footer.php';
    }
}