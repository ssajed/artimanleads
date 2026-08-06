# ArtimanLeads — AI Project Context

## 1. Project Identity

Project: ArtimanLeads

Type: CRM تخصصی مدیریت لید، پروژه و فرآیند فروش

Company: Artiman Sanat

Production Environment:
- cPanel
- PHP 8.2.x
- Laravel 11.x
- MySQL

Repository:
- GitHub: ssajed/artimanleads
- Branch: master

---

## 2. Technology Stack

Backend:
- Laravel 11
- PHP 8.2+
- MySQL

Frontend:
- Blade
- Tailwind CSS 3
- Bootstrap 5 در بخش‌هایی از پروژه
- Vite
- Alpine.js

Assets:
- Tailwind باید به صورت Local Build شود.
- استفاده از Tailwind CDN ممنوع است.
- CSS/JS باید از طریق Vite و public/build ارائه شود.

Production:
- Node/NPM روی cPanel موجود نیست.
- Build frontend باید در محیط دارای Node/NPM انجام شود و خروجی public/build به Production منتقل شود.

---

## 3. Important Production Rules

1. قبل از تغییر معماری موجود، فایل‌های مرتبط بررسی شوند.
2. قابلیت موجود بدون دلیل حذف نشود.
3. Routeهای موجود بدون بررسی وابستگی‌ها تغییر نام داده نشوند.
4. از Tailwind CDN استفاده نشود.
5. اطلاعات حساس مانند .env هرگز وارد Git نشود.
6. فایل‌های runtime و upload نباید وارد Git شوند.
7. تغییرات Production باید با cPanel سازگار باشند.
8. قبل از حذف یا تغییر فایل‌های مهم، وابستگی‌های آن بررسی شود.
9. هیچ Migration یا تغییر دیتابیس destructive بدون تأیید انجام نشود.
10. بعد از هر تغییر مهم، Laravel cache پاک شود و وضعیت Git بررسی شود.

---

## 4. Current Git Workflow

برای ارسال تغییرات:

git add .
git status
git commit -m "Description"
git push origin master

برای مشاهده فایل‌هایی که در Commit تغییر کرده‌اند:

git show --stat --oneline HEAD

برای مشاهده دقیق تغییرات:

git show --format=fuller HEAD

برای بررسی وضعیت:

git status

---

## 5. Laravel Cache

بعد از تغییرات مهم:

php artisan optimize:clear

در صورت نیاز:

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

---

## 6. Current Frontend Architecture

Main layout:

resources/views/layouts/app.blade.php

Vite:

@vite(['resources/css/app.css', 'resources/js/app.js'])

Tailwind source:

resources/css/app.css

Build output:

public/build/

Current build must contain:
- CSS
- JS
- manifest.json

Tailwind configuration:

tailwind.config.js

PostCSS configuration:

postcss.config.js

---

## 7. Current Application Areas

The application is intended to manage:

- Leads
- Projects
- Sales process
- Assignments
- Calls
- Notifications
- Users
- Roles
- Permissions
- Equipment
- Backup
- Reports
- Follow-ups
- Sales activities

---

## 8. User Roles

Current main roles:

- admin
- sales_manager
- sales_expert
- marketer

Admin has elevated system-management permissions.

Administrative operations should be protected by appropriate middleware.

---

## 9. Authentication

Application uses Laravel authentication.

Authenticated routes use:

auth

Verified routes may use:

auth, verified

Admin routes use:

auth, admin

---

## 10. Admin Area

Admin routes use prefix:

/admin

Current administrative functionality includes:

- User management
- User editing
- User role management
- User deletion
- Backup management

Admin middleware:

app/Http/Middleware/AdminMiddleware.php

---

## 11. Backup Architecture

Backup functionality exists in the application.

Important rule:

There may be multiple Backup controllers/services.

Before modifying backup functionality:
1. Inspect routes/web.php
2. Inspect BackupController
3. Inspect Admin BackupController
4. Inspect BackupService
5. Inspect navigation references
6. Verify route names

Never assume a route name exists.

---

## 12. Important Existing Components

Known files/components:

app/Http/Controllers/Admin/UserController.php

app/Http/Middleware/AdminMiddleware.php

app/Services/BackupService.php

app/Http/Kernel.php

resources/views/layouts/app.blade.php

resources/views/layouts/navigation.blade.php

resources/css/app.css

routes/web.php

---

## 13. Route Rules

Before using:

route('some.name')

verify the route exists using:

php artisan route:list

For searching a specific route:

php artisan route:list | grep backup

Do not create duplicate route names.

---

## 14. Current Frontend Problem That Was Fixed

Dashboard previously became unstyled because the main application layout did not load the Vite assets.

The problem was fixed by adding:

@vite(['resources/css/app.css', 'resources/js/app.js'])

to:

resources/views/layouts/app.blade.php

Tailwind CDN dependency was removed.

Current Dashboard status:

DONE / WORKING

---

## 15. Current Development Philosophy

The project should be developed incrementally.

Do not rewrite the whole application.

Each phase should:
1. Analyze existing implementation.
2. Identify dependencies.
3. Implement the smallest safe change.
4. Test.
5. Clear cache if necessary.
6. Check routes.
7. Check Git diff.
8. Commit.
9. Push to GitHub.

---

## 16. AI Instructions

When working on this project:

- First read this file.
- Do not guess existing architecture.
- Inspect relevant files before changing them.
- Do not assume route names.
- Do not assume controllers exist.
- Do not replace working functionality unnecessarily.
- Prefer incremental changes.
- Keep changes production-safe.
- Explain only important decisions.
- Avoid unnecessary long explanations.
- When a command can safely perform the required task, provide the command directly.
- Keep communication concise.

If information is missing, inspect the project first.

---

## 17. Current Phase

Current project phase:

P1 — Core System Stabilization & Foundation

Current known completed areas:

- Authentication
- Dashboard
- Layout
- Tailwind local build
- Vite assets
- Admin foundation
- User management foundation
- Backup foundation

---

## 18. Next Development Priority

Before adding new large features:

1. Stabilize architecture
2. Verify permissions
3. Verify role-based access
4. Verify routes
5. Verify CRUD modules
6. Standardize UI
7. Improve dashboard
8. Add audit/logging where necessary
9. Improve reporting
10. Improve backup/recovery
11. Security hardening
12. Production optimization

---

## 19. Change Log

Keep major architectural decisions here.

### 2026-08-06
- Tailwind CDN dependency identified.
- Local Tailwind/Vite build confirmed.
- app.blade.php updated to load Vite assets.
- Dashboard styling restored.
- GitHub synchronization confirmed.
- AI project context introduced.

---

## 20. Golden Rule

DO NOT BREAK WORKING FEATURES.

Before changing something:

UNDERSTAND → CHECK DEPENDENCIES → CHANGE → TEST → COMMIT → PUSH
