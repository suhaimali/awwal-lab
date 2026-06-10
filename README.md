# SUHAIM SOFT - Advanced Laboratory Management System

SUHAIM SOFT is a comprehensive, highly secure, and modern Laboratory Information Management System (LIMS) built on the powerful **Laravel 11** framework. It is meticulously designed to streamline the management of clinical patients, laboratory tests, dynamic reference intervals, billing, and highly specialized diagnostic reporting.

## 🚀 Key Features

* **Robust Patient Management:** A complete, secure patient registry with live search and rapid AJAX-based CRUD operations.
* **Laboratory Tests & Billing:** Dynamic pricing, detailed test configuration, discount application, and comprehensive payment tracking.
* **Advanced Clinical Setup:** Highly specialized biological reference intervals based on age, gender, and clinical logic (e.g., standard min-max boundaries vs. immunoassay textual results).
* **Automated PDF Invoicing:** Client-side, instant PDF invoice generation powered by `jsPDF` and `AutoTable`, featuring beautifully aligned, professional layouts and dynamic calculations.
* **Server-Side PDF Partials:** Clean, scoped Blade templates (`report.blade.php`, `invoice.blade.php`) designed without HTML boilerplate for flexible integration into modals, layouts, and direct PDF generation.
* **Diagnostic Test Reports:** Generate, manage, and securely print clinical reports with custom dynamic templates, flag templates, and authorized doctor signatures.
* **Optimized Workflow UI:** Fast, inline "Edit" buttons directly linked to drop-down fields across reports.
* **Master Data Configuration:** Manage clinical units of measurement, reference text templates, and flag templates from a centralized UI.
* **Progressive Web App (PWA):** Fully PWA-ready with service workers, manifest configuration, and installability on mobile/desktop devices.

## 🛡️ Enterprise-Grade Security & Architecture

* **100% SQL Driven:** All application state is securely stored in MySQL using Eloquent ORM.
* **Modular Blade Layouts:** Organized Laravel Blade architecture with reusable partials.
* **Zero-Tracking Policies:** Removal of unnecessary tracking scripts and local-only data handling.
* **Security Protocols:** Authenticated routes, CSRF protection, secure sessions, and browser storage cleanup.

## 💻 Tech Stack

* **Backend:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL
* **Frontend Engine:** Laravel Blade
* **Frontend:** HTML5, CSS3, JavaScript (jQuery + AJAX), Bootstrap 5
* **Plugins:** DataTables, Select2, SweetAlert2, jsPDF, Moment.js
* **Design:** Responsive UI with FontAwesome 6 icons.

## ⚙️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/suhaimali/awwal-lab.git
cd awwal-lab
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

Copy the environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file and configure your MySQL database credentials.

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Build Frontend Assets (Optional for Development)

```bash
npm run dev
```

### 6. Start the Application

```bash
php artisan serve
```

Open your browser and visit:

```
http://127.0.0.1:8000
```

## 🔧 Troubleshooting

### Missing `bootstrap/cache` Directory

If you encounter errors related to `bootstrap/cache`, run the following commands:

```powershell
dir bootstrap
```

If the `cache` folder does not exist, create it:

```powershell
mkdir bootstrap\cache
```

Verify that the folder now exists:

```powershell
dir bootstrap
```

You should see a structure similar to:

```text
bootstrap
│
├── app.php
├── providers.php
└── cache
```

Then clear and rebuild the Laravel caches:

```powershell
composer dump-autoload
php artisan optimize:clear
php artisan serve
```

If `mkdir bootstrap\cache` reports that the folder already exists or another error occurs, check the directory contents:

```powershell
dir bootstrap
dir bootstrap\cache
```

> **Note:** This issue can occur after a Git clone, merge, or rebase because Git does not track empty directories. Creating the `bootstrap/cache` folder resolves the problem.

## 🔒 Security Maintenance

To check and automatically fix known npm package vulnerabilities:

```bash
npm audit
npm audit fix
```

If needed, update development dependencies:

```bash
npm install concurrently@latest --save-dev
npm audit fix
```

## 📤 Updating and Pushing Changes

```bash
git add .
git commit -m "Update project files"
git push origin main
```

## 📜 License

This project is proprietary. Unauthorized copying, distribution, or modification of this project, via any medium, is strictly prohibited without prior written permission.
