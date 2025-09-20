<?php
// config/app.php

return [
    'app_name' => 'سامانه حسابداری شخصی',
    'app_url' => 'fintra.test',
    'backup_dir' => __DIR__ . '/../backups/',
    'export_dir' => __DIR__ . '/../exports/',
    'log_dir' => __DIR__ . '/../logs/',
    'enable_email_reminders' => false,
    'email_from' => 'noreply@localhost',
    'email_name' => 'Fintra سامانه حسابداری',
    
    // Database configuration
    'db_host' => 'localhost',
    'db_name' => 'fintra',
    'db_username' => 'archtek',
    'db_password' => '2002',
];