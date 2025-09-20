-- Backup generated on 2025-09-18 02:53:56
-- Database: accounting_app

;

INSERT INTO `account_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('1', '1', 'حساب بانکی', '2025-09-17 01:00:43');
INSERT INTO `account_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('2', '1', 'کارت اعتباری', '2025-09-17 01:00:43');
INSERT INTO `account_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('3', '1', 'کیف پول نقد', '2025-09-17 01:00:43');

;

INSERT INTO `accounts` (`id`, `user_id`, `category_id`, `title`, `account_number`, `card_number`, `initial_balance`, `created_at`, `updated_at`) VALUES ('1', '1', '1', 'حساب ملی', '1234567890', '5859831219005724', '5000000', '2025-09-17 01:00:43', '2025-09-17 01:50:33');
INSERT INTO `accounts` (`id`, `user_id`, `category_id`, `title`, `account_number`, `card_number`, `initial_balance`, `created_at`, `updated_at`) VALUES ('2', '1', '2', 'کارت ملی', NULL, '5859831219005724', '2000000', '2025-09-17 01:00:43', '2025-09-17 01:49:27');
INSERT INTO `accounts` (`id`, `user_id`, `category_id`, `title`, `account_number`, `card_number`, `initial_balance`, `created_at`, `updated_at`) VALUES ('3', '1', '3', 'کیف پول خانه', NULL, NULL, '1000000', '2025-09-17 01:00:43', '2025-09-17 01:00:43');

;

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `context`, `created_at`) VALUES ('1', '1', 'delete_transaction', 'Transaction ID: 6', '2025-09-17 02:34:58');

;

;

;

INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('1', '2', '2025-09-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('2', '2', '2025-10-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('3', '2', '2025-11-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('4', '2', '2025-12-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('5', '2', '2026-01-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('6', '2', '2026-02-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('7', '2', '2026-03-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('8', '2', '2026-04-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('9', '2', '2026-05-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('10', '2', '2026-06-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('11', '2', '2026-07-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('12', '2', '2026-08-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('13', '2', '2026-09-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('14', '2', '2026-10-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('15', '2', '2026-11-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('16', '2', '2026-12-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('17', '2', '2027-01-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('18', '2', '2027-02-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('19', '2', '2027-03-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('20', '2', '2027-04-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('21', '2', '2027-05-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('22', '2', '2027-06-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('23', '2', '2027-07-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('24', '2', '2027-08-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('25', '2', '2027-09-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('26', '2', '2027-10-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('27', '2', '2027-11-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('28', '2', '2027-12-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('29', '2', '2028-01-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('30', '2', '2028-02-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('31', '2', '2028-03-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('32', '2', '2028-04-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('33', '2', '2028-05-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('34', '2', '2028-06-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('35', '2', '2028-07-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');
INSERT INTO `loan_installments` (`id`, `loan_id`, `due_date`, `amount`, `status`, `paid_date`, `created_at`) VALUES ('36', '2', '2028-08-18', '1961643', 'pending', NULL, '2025-09-18 01:03:30');

;

INSERT INTO `loans` (`id`, `user_id`, `lender_name`, `principal`, `interest`, `start_date`, `term_months`, `installment_amount`, `next_due_date`, `status`, `description`, `created_at`, `updated_at`) VALUES ('2', '1', 'بانک تجارت', '50000000', '24.00', '2025-09-18', '36', '1961643', '2025-09-18', 'active', '', '2025-09-18 01:03:30', '2025-09-18 01:03:30');

;

;

INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('1', '1', 'غذا و نوشیدنی', '2025-09-17 01:00:43');
INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('2', '1', 'حقوق و دستمزد', '2025-09-17 01:00:43');
INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('3', '1', 'اجاره', '2025-09-17 01:00:43');
INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('4', '1', 'سرگرمی', '2025-09-17 01:00:43');
INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `created_at`) VALUES ('5', '1', 'حمل و نقل', '2025-09-17 01:00:43');

;

INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `type`, `category_id`, `amount`, `trans_date`, `description`, `created_at`, `updated_at`) VALUES ('1', '1', '1', 'income', '2', '15000000', '2025-04-01', 'حقوق ماهانه', '2025-09-17 01:00:43', '2025-09-17 01:00:43');
INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `type`, `category_id`, `amount`, `trans_date`, `description`, `created_at`, `updated_at`) VALUES ('2', '1', '1', 'expense', '3', '8000000', '2025-04-05', 'پرداخت اجاره مسکن', '2025-09-17 01:00:43', '2025-09-17 01:00:43');
INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `type`, `category_id`, `amount`, `trans_date`, `description`, `created_at`, `updated_at`) VALUES ('3', '1', '2', 'expense', '1', '1200000', '2025-04-07', 'ناهار بیرون', '2025-09-17 01:00:43', '2025-09-17 01:00:43');
INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `type`, `category_id`, `amount`, `trans_date`, `description`, `created_at`, `updated_at`) VALUES ('4', '1', '3', 'expense', '5', '300000', '2025-04-08', 'تاکسی', '2025-09-17 01:00:43', '2025-09-17 01:00:43');
INSERT INTO `transactions` (`id`, `user_id`, `account_id`, `type`, `category_id`, `amount`, `trans_date`, `description`, `created_at`, `updated_at`) VALUES ('5', '1', '1', 'expense', '4', '500000', '2025-04-10', 'فیلم سینما', '2025-09-17 01:00:43', '2025-09-17 01:00:43');

;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `updated_at`) VALUES ('1', 'کاربر نمونه', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09123456789', '2025-09-17 01:00:43', '2025-09-17 01:28:32');

