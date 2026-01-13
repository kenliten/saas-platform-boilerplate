# Zero-Dependency PHP SaaS Platform

A lightweight, robust, and completely adaptable SaaS boilerplate built with **PHP 8**, **MySQL**, and **Bootstrap 5**. No Composer, no npm build steps, no bloat. Just pure, clean code ready for production.

## 🚀 Why this project?

Modern PHP frameworks are powerful but heavy. This project provides a **Professional SaaS Architecture** without the complexity of vendor locks, dependency hell, or heavy compile steps.

- **Zero Dependencies**: Runs on any standard LAMP/LEMP stack.
- **Full-Featured**: Auth, Billing (Stripe/PayPal), Dashboard, Roles, and more.
- **Secure**: CSRF protection, Rate Limiting, Secure Headers, and Prepared Statements built-in.
- **Developer Friendly**: CLI tools for scaffolding, migrations, and backups included.

## ✨ Features

- **Core Framework**: Custom Autoloader, Router, and MVC architecture.
- **Authentication**: Secure Session-based Login/Logout with `bcrypt`.
- **Database**: Lightweight PDO wrapper with Migration Runner.
- **Billing Engine**: Abstract Billing adapter with **Stripe** and **PayPal** support built-in.
- **Admin Dashboard**: Analytics, User Growth Charts, and Reporting.
- **Plans & Subscriptions**: Database-driven Plan management (Free, Basic, Pro).
- **Profile Management**: User profiles with Avatar upload and optional fields.
- **Notifications**: Integrated notification system (Global & User-scoped).
- **Security**: CSRF Middleware, IP-based Rate Limiting, Secure File Uploads.
- **Dev Ergonomics**: Global helpers for Auth (`user()`, `is_authenticated()`), i18n (`__`), and Env (`env`).
- **Dev Tools**:
    - `php scripts/scan_translations.php`: Scans views for `__('key')` and updates JSON files.
    - CLI scripts to scaffold modules, seed data, and create admins.
- **Ops Ready**: Automated backups (DB + Uploads), Health monitoring, and Log rotation.

## 🛠️ Getting Started

### Prerequisites
- PHP 8.0+
- MySQL or MariaDB
- Apache or Nginx

### Installation

1. **Clone the repo**
   ```bash
   git clone https://github.com/yourusername/saas-platform-base.git
   cd saas-platform-base
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   # Edit .env with your Database credentials
   ```

3. **Initialize**
   Run the master setup script (Linux/Mac/Git Bash):
   ```bash
   ./scripts/setup.sh
   # Or manually: php scripts/init_db.php && php scripts/migrate.php && php scripts/seed.php
   ```

4. **Launch**
   - **Local PHP Server**:
     ```bash
     cd public && php -S localhost:8000
     ```
   - **Docker**:
     ```bash
     # Build and Run
     bash scripts/containerize.sh
     
     # Initialize DB (First Run only)
     docker-compose -f docker/docker-compose.yml exec app php scripts/init_db.php
     docker-compose -f docker/docker-compose.yml exec app php scripts/migrate.php
     docker-compose -f docker/docker-compose.yml exec app php scripts/seed.php
     ```

5. **Login**
   - **URL**: `http://localhost:8000`
   - **Email**: `admin@example.com`
   - **Password**: `password`

## ⌨️ CLI Tools

- **Create New Module** (Controller + Model + View):
  `php scripts/new-module.php Product`
- **Create Admin User**:
  `php scripts/create-admin.php`
- **Backup Database**:
  `php scripts/backup-db.php`

## 🤝 Community & Support

This project is designed to be a solid foundation for your next SaaS venture. 

- **Follow the Dev**: [oreyesgalay](https://twitter.com/oreyesgalay) on Twitter & [LinkedIn](https://linkedin.com/in/oreyesgalay).
- **Contribute**: PRs are welcome! Help us keep it simple and powerful.

### ❤️ Support the Project
If you saved time or learned something cool, consider buying me a coffee!
[Example: Buy Me a Coffee Link / Placeholder]

---
*Built with ❤️ by Otoniel & The Community.*
