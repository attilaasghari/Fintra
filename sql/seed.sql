-- sql/seed.sql

INSERT INTO users (name, email, password, phone, created_at, updated_at) VALUES
('کاربر نمونه', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456789', NOW(), NOW());

-- password: demo123 (hashed using password_hash('demo123', PASSWORD_DEFAULT))

INSERT INTO account_categories (user_id, name, created_at) VALUES
(1, 'حساب بانکی', NOW()),
(1, 'کارت اعتباری', NOW()),
(1, 'کیف پول نقد', NOW());

INSERT INTO accounts (user_id, category_id, title, account_number, card_number, initial_balance, created_at, updated_at) VALUES
(1, 1, 'حساب ملی', '1234567890', '6037****1234', 5000000, NOW(), NOW()),
(1, 2, 'کارت ملی', NULL, '6037****5678', 2000000, NOW(), NOW()),
(1, 3, 'کیف پول خانه', NULL, NULL, 1000000, NOW(), NOW());

INSERT INTO transaction_categories (user_id, name, created_at) VALUES
(1, 'غذا و نوشیدنی', NOW()),
(1, 'حقوق و دستمزد', NOW()),
(1, 'اجاره', NOW()),
(1, 'سرگرمی', NOW()),
(1, 'حمل و نقل', NOW());

INSERT INTO transactions (user_id, account_id, type, category_id, amount, trans_date, description, created_at, updated_at) VALUES
(1, 1, 'income', 2, 15000000, '2025-04-01', 'حقوق ماهانه', NOW(), NOW()),
(1, 1, 'expense', 3, 8000000, '2025-04-05', 'پرداخت اجاره مسکن', NOW(), NOW()),
(1, 2, 'expense', 1, 1200000, '2025-04-07', 'ناهار بیرون', NOW(), NOW()),
(1, 3, 'expense', 5, 300000, '2025-04-08', 'تاکسی', NOW(), NOW()),
(1, 1, 'expense', 4, 500000, '2025-04-10', 'فیلم سینما', NOW(), NOW());