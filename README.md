## English Version

### 🏷️ Project Overview

**Fintra** is a **free and open-source personal accounting system** designed for Persian-speaking users. It helps you professionally manage your income, expenses, debts, credits, and loans — all without internet (runs on localhost). With full Jalali calendar support, PDF/Excel exports, smart reminders, and RTL UI, Fintra is the ideal tool for personal and family financial management.

---

### ✨ Key Features

- ✅ **Multi-Account Management**: Track bank accounts, credit cards, and cash wallets simultaneously
- ✅ **Transaction Logging**: Categorize income/expenses with advanced filtering
- ✅ **Debt/Credit Management**: Register debts/credits with partial payment tracking
- ✅ **Loan & Installment Tracking**: Auto-calculate installments, track payments, show progress
- ✅ **Smart Reporting**: Export transactions, debts, loans to PDF and Excel
- ✅ **Backup & Restore**: Create SQL backups and restore in emergencies
- ✅ **Activity Logging**: Audit all user actions for transparency and security
- ✅ **Smart Reminders**: Get notifications for upcoming due dates via cron job
- ✅ **Jalali Support**: Display and input dates in Persian format (1404/07/25)
- ✅ **Localhost Only**: No internet or external server required

---

### 🚀 Quick Start

#### Prerequisites

- PHP 8.0+
- MySQL 5.7+
- Composer
- Apache or Nginx (or PHP Built-in Server)

#### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/fintra.git
   cd fintra
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Create database:**
   ```sql
   CREATE DATABASE fintra CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

4. **Import schema and sample **
   ```bash
   mysql -u root -p fintra < sql/schema.sql
   mysql -u root -p fintra < sql/seed.sql
   ```

5. **Configure database:**
   Edit `config/database.php` with your DB credentials.

6. **Create directories:**
   ```bash
   mkdir -p backups exports logs
   chmod 755 backups exports logs
   ```

7. **Start server:**
   ```bash
   cd public
   php -S localhost:8000
   ```

8. **Access:**
   Open browser and visit:
   ```
   http://localhost:8000
   ```

---

### 👤 Login Credentials

- **Email:** `demo@example.com`
- **Password:** `demo123`

---

### ⚙️ Cron Setup (Reminders)

To enable automatic reminders:

#### Linux/Mac:
```bash
crontab -e
```
Add this line (daily at 9 AM):
```bash
0 9 * * * /usr/bin/php /path/to/fintra/cron/reminder_runner.php >> /path/to/fintra/logs/reminder.log 2>&1
```

#### Windows (Task Scheduler):
- **Program:** `C:\path\to\php.exe`
- **Arguments:** `C:\path\to\fintra\cron\reminder_runner.php`
- **Schedule:** Daily at 9 AM

---

### 📄 Project Structure

```
fintra/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── public/
│   └── index.php
├── config/
├── cron/
├── sql/
├── backups/
├── exports/
└── logs/
```

---

### 🤝 Contribution

Fintra is open-source! All contributions are welcome:
- Bug reports
- Feature requests
- Documentation improvements
- Translation and localization

---

### 📞 Support

For any issues:
- **Email:** `fintra@vitren.ir`
- **Website:** [vitren.ir](https://vitren.ir)

---

### 📜 License

This project is released under the **MIT License** — meaning it’s completely free and open for personal and commercial use.

---

## 🎉 Thank You!

Whether you're a user or a contributor — thank you for being part of the Fintra community. Let’s build better financial tools, together.

---

## نسخه فارسی

### 🏷️ معرفی پروژه

**فاین ترا** یک **سامانه حسابداری شخصی کاملاً رایگان و متن‌باز** است که برای کاربران فارسی‌زبان طراحی شده است. این سامانه به شما کمک می‌کند تا درآمدها، هزینه‌ها، بدهی‌ها، طلب‌ها و وام‌های خود را به صورت حرفه‌ای و بدون نیاز به اینترنت (روی localhost) مدیریت کنید. با پشتیبانی کامل از تقویم جلالی، خروجی PDF/Excel، یادآوری‌های هوشمند و رابط کاربری RTL، فاین ترا ابزاری ایده‌آل برای مدیریت مالی خانواده و افراد است.

---

### ✨ ویژگی‌های کلیدی

- ✅ **مدیریت چندحسابی**: ثبت همزمان حساب‌های بانکی، کارت‌های اعتباری و کیف پول نقد
- ✅ **ثبت تراکنش‌ها**: دسته‌بندی درآمدها و هزینه‌ها با قابلیت فیلتر و جستجو
- ✅ **مدیریت بدهی/طلب**: ثبت بدهی‌ها و طلب‌ها با امکان پرداخت جزئی و پیگیری مانده
- ✅ **مدیریت وام و اقساط**: محاسبه خودکار اقساط، پیگیری پرداخت‌ها و نمایش پیشرفت
- ✅ **گزارش‌گیری هوشمند**: خروجی PDF و Excel از تراکنش‌ها، بدهی‌ها و وام‌ها
- ✅ **پشتیبان‌گیری و بازیابی**: ایجاد نسخه پشتیبان SQL و بازیابی در لحظات بحرانی
- ✅ **لاگ فعالیت‌ها**: ثبت تمام تغییرات کاربران برای شفافیت و امنیت
- ✅ **یادآوری‌های خودکار**: اعلان برای سررسیدهای نزدیک از طریق cron job
- ✅ **پشتیبانی جلالی**: نمایش و ثبت تاریخ‌ها به فرمت شمسی (۱۴۰۴/۰۷/۲۵)
- ✅ **اجرا روی localhost**: نیاز به اینترنت یا سرور خارجی ندارد

---

### 🚀 شروع سریع

#### پیش‌نیازها

- PHP 8.0+
- MySQL 5.7+
- Composer
- Apache یا Nginx (یا PHP Built-in Server)

#### نصب

1. **کلون کردن پروژه:**
   ```bash
   git clone https://github.com/yourusername/fintra.git
   cd fintra
   ```

2. **نصب وابستگی‌ها:**
   ```bash
   composer install
   ```

3. **ایجاد پایگاه داده:**
   ```sql
   CREATE DATABASE fintra CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

4. **وارد کردن اسکیما و داده‌های نمونه:**
   ```bash
   mysql -u root -p fintra < sql/schema.sql
   mysql -u root -p fintra < sql/seed.sql
   ```

5. **تنظیم فایل پیکربندی:**
   ویرایش `config/database.php` و تنظیم اطلاعات اتصال به دیتابیس.

6. **ایجاد دایرکتوری‌ها:**
   ```bash
   mkdir -p backups exports logs
   chmod 755 backups exports logs
   ```

7. **راه‌اندازی سرور:**
   ```bash
   cd public
   php -S localhost:8000
   ```

8. **دسترسی:**
   باز کردن مرورگر و مراجعه به:
   ```
   http://localhost:8000
   ```

---

### 👤 ورود به سامانه

- **ایمیل:** `demo@example.com`
- **رمز عبور:** `demo123`

---

### ⚙️ تنظیمات Cron (یادآوری‌ها)

برای فعال‌سازی یادآوری‌های خودکار:

#### Linux/Mac:
```bash
crontab -e
```
اضافه کردن خط زیر (هر روز ساعت 9 صبح):
```bash
0 9 * * * /usr/bin/php /path/to/fintra/cron/reminder_runner.php >> /path/to/fintra/logs/reminder.log 2>&1
```

#### Windows (Task Scheduler):
- **برنامه:** `C:\path\to\php.exe`
- **آرگومان:** `C:\path\to\fintra\cron\reminder_runner.php`
- **زمان‌بندی:** روزانه ساعت 9 صبح

---

### 📄 ساختار پروژه

```
fintra/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── public/
│   └── index.php
├── config/
├── cron/
├── sql/
├── backups/
├── exports/
└── logs/
```

---

### 🤝 مشارکت

فاین ترا یک پروژه متن‌باز است! هرگونه مشارکتی خوش‌آمد است:
- گزارش باگ‌ها
- پیشنهاد ویژگی‌های جدید
- بهبود مستندات
- ترجمه و بومی‌سازی

---

### 📞 پشتیبانی

در صورت بروز هرگونه مشکل:
- **ایمیل:** `fintra@vitren.ir`
- **وبسایت:** [vitren.ir](https://vitren.ir)

---

### 📜 لایسنس

این پروژه تحت لایسنس **MIT** منتشر شده است — یعنی کاملاً رایگان و آزاد برای استفاده شخصی و تجاری.

---

