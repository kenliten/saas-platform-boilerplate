# Instructions for AI Agents

This document defines the architectural patterns, coding conventions, and available tools for this **Zero-Dependency PHP SaaS Platform**.

## 1. Project Philosophy
- **Zero External Dependencies**: Do NOT Suggest Composer packages unless explicitly requested. Use core PHP 8.x features.
- **MVC Architecture**: Strict separation of Controllers, Models, and Views.
- **Simplicity**: Prefer simple, readable implementations over complex abstractions.
- **Security First**: Always use `Csrf::field()` in forms, `e()` for output, and prepared statements for SQL.

## 2. Architecture & Patterns

### Directory Structure
- `public/`: Web root. Only `index.php` and assets.
- `app/Core/`: Framework internals (Router, Database, Session, Validator).
- `app/Controllers/`: Request handlers. Extend `App\Core\BaseController`.
- `app/Models/`: Data access. Extend `App\Core\BaseModel`.
- `app/Views/`: Native PHP templates.
- `app/Services/`: Business logic (Billing, Notifications, Uploads).
- `app/Migrations/`: Database schema changes.
- `scripts/`: CLI tools.

### Database Access
- Use `App\Core\Database::getConnection()` (PDO) or extend `App\Core\BaseModel`.
- **Always** use prepared statements: `$db->query("SELECT * FROM users WHERE id = ?", [$id])`.
- **Migrations**: Create `.sql` files in `app/Migrations/`. Naming convention: `XXX_description.sql`.

### Authentication & Routing
- **Middleware**: Defined in `app/Middlewares/`. Attach via `$router->get(...)->middleware(AuthMiddleware::class)`.
- **Session**: Use `App\Core\Session::get/set`. Do not access `$_SESSION` directly.

## 3. Available CLI Tools
Use these scripts to perform common tasks instead of manual coding when possible.

- **Docker/Containerization**:
  `bash scripts/containerize.sh` (Start app in Docker)

- **Scaffold Module**: Create Controller/Model/View.
  `php scripts/new-module.php [ModuleName]`

- **Database Management**:
  `php scripts/migrate.php` (Apply migrations)
  `php scripts/seed.php` (Seed data)
  `php scripts/backup-db.php` (Dump DB)

- **Admin Creation**:
  `php scripts/create-admin.php`

## 4. Coding Standards (PSR-12 inspired)
- 4 spaces indentation.
- Braces on new lines for classes/methods.
- Strict types not enforced but recommended for new services.

### Global Helpers
- `env($key, $default)`: Retrieve env var.
- `__($key)`: i18n translation. Supports `?lang=es` and uses `app/Config/i18n/{lang}.json`.
- `e($string)`: Escape HTML.
- `is_authenticated()`: Check login status.
- `user()`: Get current user model.
- `user_role()`: Get current role.
- `user_payment_status()`: Get current plan slug.

## 5. Deployment
- **Health Check**: `/health` endpoint is available.
- **Backups**: `scripts/backup-full.php` handles DB and Uploads.
- **Logs**: `scripts/rotate-logs.php` handles rotation.
