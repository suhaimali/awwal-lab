# Awwal Lab

A modern Laravel web application built with Laravel 12, Tailwind CSS v4, Vite, and SQLite.

---

## ⚡ Quick Start (Recommended)

If you have PHP, Composer, and Node.js installed, you can set up the entire project with a single command:

```bash
# 1. Clone the repository
git clone https://github.com/your-username/awwal-lab.in.git
cd awwal-lab.in

# 2. Run the automated setup script
composer setup

# 3. Start the unified development environment
composer dev
```

The `composer dev` command will concurrently run:
*   **Laravel Local Server** (on `http://127.0.0.1:8000`)
*   **Vite Dev Server** (for Tailwind CSS and frontend assets hot-reloads)
*   **Queue Worker** (to process background jobs)
*   **Laravel Pail** (to tail local logs directly in the console)

---

## 🛠️ Step-by-Step Manual Setup

If you prefer to run the setup steps individually, follow the guide below:

### 1. Prerequisites

Ensure you have the following installed on your machine:
*   **PHP** >= 8.2 (with SQLite extensions enabled: `pdo_sqlite` and `sqlite3`)
*   **Composer** (PHP Package Manager)
*   **Node.js** & **NPM** (for compiling frontend assets)

### 2. Install Dependencies

Install the PHP and Node packages:
```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the example environment file:
```bash
# On Linux/macOS
cp .env.example .env

# On Windows (CMD)
copy .env.example .env

# On Windows (PowerShell)
Copy-Item .env.example .env
```

### 4. Generate Application Key

Laravel requires a unique application key to encrypt user sessions and other sensitive data:
```bash
php artisan key:generate
```

### 5. Database Setup

The project uses a lightweight **SQLite** database by default.
1. Create an empty SQLite file:
   ```bash
   # On Windows (PowerShell)
   New-Item -ItemType File -Path database/database.sqlite -Force
   
   # On Linux/macOS/Git Bash
   touch database/database.sqlite
   ```
2. Run migrations to build the schema:
   ```bash
   php artisan migrate
   ```
3. (Optional) Seed the database with initial/test data (creates a default user: `test@example.com`):
   ```bash
   php artisan db:seed
   ```

### 6. Compile Assets

To build frontend assets using Vite and Tailwind CSS v4:

*   **For Development (Live updates):**
    ```bash
    npm run dev
    ```
*   **For Production:**
    ```bash
    npm run build
    ```

---

## 🚀 Running the Project

You can run the project components individually if you do not want to use the unified `composer dev` command:

1. **Start the Laravel Server:**
   ```bash
   php artisan serve
   ```
   *The application will be accessible at `http://127.0.0.1:8000`.*

2. **Start the Vite Dev Server:**
   ```bash
   npm run dev
   ```

3. **Start the Queue Listener:**
   ```bash
   php artisan queue:listen
   ```

---

## 🧪 Testing

To run the automated PHPUnit tests:

```bash
composer test
# or
php artisan test
```
