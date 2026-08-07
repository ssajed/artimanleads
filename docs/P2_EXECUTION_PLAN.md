# P2 — Project Model Refactor Execution Plan

## هدف

حذف فیلدهای تکراری از جدول `projects`:

- `purchase_stage` → استفاده از `purchase_status`
- `project_level` → استفاده از `level`

## قوانین اجرا

این Task شامل تغییر دیتابیس است.

قبل از Migration:

1. Git status بررسی شود.
2. Backup دیتابیس تهیه شود.
3. Mapping داده‌های موجود بررسی شود.
4. تمام References فیلدهای قدیمی بررسی شود.
5. تغییرات کد آماده شود.
6. git diff بررسی شود.
7. فقط پس از تأیید صریح ساجد Migration اجرا شود.

## فایل‌های احتمالی

app/Models/Project.php
app/Http/Controllers/ProjectController.php
app/Services/ProjectService.php
app/Services/LeadScoreService.php
resources/views/projects/create.blade.php
resources/views/projects/edit.blade.php
resources/views/projects/show.blade.php
database/migrations/

## Mapping

purchase_stage → purchase_status
project_level → level

Mapping نهایی باید بر اساس داده واقعی دیتابیس تعیین شود و نباید حدس زده شود.

## Git Protocol

قبل از Commit:

git status --short
git diff --stat
git diff

بعد از تأیید:

git add .
git commit -m "refactor: remove duplicate project fields"
git push origin master

## ممنوع

بدون تأیید صریح ساجد:

php artisan migrate
DROP COLUMN
DELETE
TRUNCATE
rm
git commit
git push

اجرا نشود.

## مجوز اجرا

فقط این دو عبارت مجوز اجرای تغییرات هستند:

"تأیید است، اجرا کن"

"انجامش بده"

## وضعیت

P2 در مرحله Analysis / Planning است.
