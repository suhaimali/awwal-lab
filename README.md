# Awwal Lab Management System

Awwal Lab is a comprehensive, secure, and modern Laboratory Information Management System (LIMS) built with Laravel. It streamlines the management of patients, laboratory tests, clinical reference intervals, billing, and comprehensive diagnostic reports.

## Features

- **Patient Management:** Complete patient registry with secure data storage.
- **Laboratory Tests & Billing:** Dynamic pricing, test configuration, and payment tracking.
- **Clinical Setup:** Advanced biological reference intervals based on age, gender, and specialized clinical logic (e.g., standard min-max vs immunoassay).
- **Test Reports:** Generate, manage, and print clinical reports with custom dynamic templates and doctor signatures.
- **Master Data Configuration:** Manage units of measurement, reference text templates, and flag templates.
- **High Security:** 
  - Fully authenticated routes and protected APIs.
  - Strict HTTPS enforcement.
  - Secure Sessions & localized database interactions.
- **Modern UI:** Responsive, highly styled interface featuring live search, modals, and dynamic AJAX-powered form submissions.

## Tech Stack

- **Backend:** Laravel (PHP)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (jQuery + AJAX), Bootstrap
- **Icons & UI:** FontAwesome, custom modern glassmorphism styling

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/suhaimali/awwal-lab.in.git
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
   *Make sure `SESSION_SECURE_COOKIE=true` is set if running in production.*

4. **Database Migration:**
   ```bash
   php artisan migrate
   ```

5. **Serve the Application:**
   ```bash
   php artisan serve
   ```
   Access the application securely at `http://127.0.0.1:8000` (HTTPS will be enforced if configured).

## Security Notes

- Local Storage is cleared actively to ensure that sensitive medical data (such as legacy patient lists or tests) is not exposed in the browser's developer tools.
- All application transactions interact securely with the backend MySQL database using Eloquent ORM.

## License

This project is proprietary. Unauthorized copying of this project, via any medium, is strictly prohibited.
