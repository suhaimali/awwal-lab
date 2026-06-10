# SUHAIM SOFT - Advanced Laboratory Management System

SUHAIM SOFT is a comprehensive, highly secure, and modern Laboratory Information Management System (LIMS) built on the powerful **Laravel 11** framework. It is meticulously designed to streamline the management of clinical patients, laboratory tests, dynamic reference intervals, billing, and highly specialized diagnostic reporting.

## 🚀 Key Features

- **Robust Patient Management:** A complete, secure patient registry with live search and rapid AJAX-based CRUD operations.
- **Laboratory Tests & Billing:** Dynamic pricing, detailed test configuration, discount application, and comprehensive payment tracking.
- **Advanced Clinical Setup:** Highly specialized biological reference intervals based on age, gender, and clinical logic (e.g., standard min-max boundaries vs. immunoassay textual results).
- **Automated PDF Invoicing:** Client-side, instant PDF invoice generation powered by `jsPDF` and `AutoTable`, featuring beautifully aligned, professional layouts and dynamic calculations.
- **Server-Side PDF Partials:** Clean, scoped Blade templates (`report.blade.php`, `invoice.blade.php`) designed without HTML boilerplate for flexible integration into modals, layouts, and direct PDF generation (e.g., via DomPDF), supporting thermal receipt layouts.
- **Diagnostic Test Reports:** Generate, manage, and securely print clinical reports with custom dynamic templates, flag templates, and authorized doctor signatures.
- **Optimized Workflow UI:** Fast, inline "Edit" buttons directly linked to drop-down fields across reports, instantly pulling up detail modals without tedious intermediate "view" steps.
- **Master Data Configuration:** Manage clinical units of measurement, reference text templates, and flag templates from a centralized UI.
- **Progressive Web App (PWA):** Fully PWA-ready with service workers, manifest configuration, and installability on mobile/desktop devices.

## 🛡️ Enterprise-Grade Security & Architecture

- **100% SQL Driven:** Completely migrated away from browser-based `localStorage`. All state (even UI preferences like sidebar toggles) is securely preserved in a normalized **MySQL** database via Eloquent ORM.
- **Modular Blade Layouts:** Clean, highly organized Laravel Blade architecture utilizing strictly partitioned partials for header, sidebar, footer, styles, and scripts.
- **Zero-Tracking Policies:** Full removal of unnecessary WebSockets and tracking scripts from vendor plugins (like PACE), guaranteeing internal data stays strictly local.
- **Security Protocols:** Fully authenticated routes, protected APIs, strict CSRF protection, secure sessions, and forced Local Storage eviction to prevent sensitive data leaks in browser DevTools.

## 💻 Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL
- **Frontend Engine:** Laravel Blade
- **Frontend Technologies:** HTML5, CSS3, JavaScript (jQuery + AJAX), Bootstrap 5
- **Plugins:** DataTables, Select2, SweetAlert2, jsPDF, Moment.js
- **Design:** Custom modern UI, glassmorphism aesthetics, responsive layouts, and FontAwesome 6 icons.

## ⚙️ Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/suhaimali/awwal-lab.git
   cd awwal-lab.in
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   Copy the example environment file and configure your database credentials.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Migration:**
   ```bash
   php artisan migrate
   ```

5. **Serve the Application:**
   ```bash
   php artisan serve
   ```
   Access the application securely at `http://127.0.0.1:8000`.

## 📜 License

This project is proprietary. Unauthorized copying of this project, via any medium, is strictly prohibited.


Run these commands one by one and send the output:

dir bootstrap

If there is no cache folder:

mkdir bootstrap\cache

Then verify:

dir bootstrap

You should see:

bootstrap
│
├── app.php
├── providers.php
└── cache

Then run:

composer dump-autoload
php artisan optimize:clear
php artisan serve

If mkdir bootstrap\cache gives an error or the folder already exists, send the output of:

dir bootstrap
dir bootstrap\cache

This usually happens after a Git merge/rebase where the bootstrap/cache directory was deleted because Git doesn't track empty folders.
