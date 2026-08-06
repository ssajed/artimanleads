# پروژه ArtimanLeads - Context Document

## اطلاعات کلی
- **نام پروژه**: ArtimanLeads (سیستم مدیریت فروش آرتیمان)
- **مسیر سرور**: `/home/cp36529/public_html/artimanleads`
- **نسخه Laravel**: 11
- **نسخه PHP**: 8.2
- **محیط اجرا**: cPanel (Production)

## وضعیت فعلی (August 2026)
- پروژه از Git برای کنترل نسخه استفاده می‌کند.
- Tailwind به‌صورت محلی نصب و Build شده است (فایل `app-BXE6TF6l.css` در `public/build` موجود است).
- `@vite` در `app.blade.php` وجود ندارد؛ به‌هم‌ریختگی Dashboard به‌همین دلیل است.
- npm روی سرور نصب نیست و نباید اجرا شود.
- Route قدیمی `backup.index` در Navigation باقی مانده که باید با `admin.backup` جایگزین شود.

## معماری فعلی
- **Laravel 11** با ساختار MVC استاندارد.
- **Eloquent ORM** برای مدل‌های اصلی: `Project`, `Assignment`, `CallLog`, `User`, `Notification`.
- **Services**: `ProjectService`, `AssignmentService`, `CallLogService`, `BackupService`, `LeadScoreService`.
- **Controllers**: `ProjectController`, `AssignmentController`, `CallLogController`, `DashboardController`, `BackupController`, `Admin/UserController`, `Admin/BackupController`.
- **Policies**: (فعلاً محدود) `ProjectPolicy`, `AssignmentPolicy`.
- **Authentication**: Laravel Breeze.

## ساختار دیتابیس
- **DB**: MySQL (cPanel)
- **جداول اصلی**: `users`, `projects`, `assignments`, `call_logs`, `notifications`, `project_contacts`, `equipment_details`, `backups`.
- **فیلدهای مهم**: `status`, `role`, `level`, `purchase_status`, `assigned_to`, `created_by`.

## مسیرهای کلیدی (Routes)
- `/dashboard` - داشبورد اصلی
- `/admin/users` - مدیریت کاربران (فقط Admin)
- `/admin/backup` - مدیریت Backup (فقط Admin)
- `/projects` - مدیریت پروژه‌ها
- `/assignments` - مدیریت ارجاع‌ها
- `/call-logs` - مدیریت تماس‌ها

## وضعیت فازهای توسعه
| فاز | عنوان | وضعیت |
|-----|-------|-------|
| P0 | اصلاح لود CSS | ✅ انجام شد |
| P1 | اصلاح مسیر Backup | ⏳ در انتظار |
| P2 | Refactor Project Model | ⏳ در برنامه |
| P3 | بهبود امنیت و تست‌ها | ⏳ در برنامه |

## نکات فنی مهم
1. **ممنوعیت استفاده از npm روی سرور** (نصب نیست).
2. **فایل‌های Build شده در `public/build`** نباید حذف شوند.
3. **تغییرات باید در Git ثبت شوند** (`master` branch).
4. **قبل از هر تغییر، فایل Context بررسی شود**.
5. **از `php artisan optimize:clear` بعد از هر تغییر استفاده شود**.

## تاریخچه تغییرات مهم
- **Aug 6, 2026**: اصلاح `app.blade.php` با `@vite` برای لود CSS محلی.
- **Aug 6, 2026**: ایجاد فایل‌های Context.

---

## 🔴 پروتکل کنترل تغییرات پروژه (قانون دائمی)

### نقش دیپی
برنامه‌نویس و تحلیلگر فنی، نه تصمیم‌گیرنده مستقل.

### سلسله‌مراتب انجام کارها

**1. بررسی**
- ابتدا `docs/AI_CONTEXT.md`
- سپس `docs/CURRENT_TASK.md`
- سپس فایل‌ها و کدهای واقعی مرتبط با Task
- در صورت نیاز Git status / log / diff

**2. تحلیل**
- مشکل واقعی را مشخص کن.
- علت را بر اساس کد موجود توضیح بده.
- فایل‌هایی که قرار است تغییر کنند را دقیق اعلام کن.
- راه‌حل پیشنهادی را ارائه کن.

**3. توقف برای تأیید**
تا زمانی که ساجد صراحتاً تأیید نکرده است:
- هیچ فایل پروژه را تغییر نده.
- هیچ کدی را ایجاد یا حذف نکن.
- هیچ `rm` اجرا نکن.
- هیچ Migration اجرا نکن.
- هیچ تغییر Database انجام نده.
- هیچ `git commit` انجام نده.
- هیچ `git push` انجام نده.
- هیچ تغییر معماری انجام نده.
- هیچ Feature جدیدی اضافه نکن.

**4. بعد از تأیید ساجد**
فقط همان Task تأییدشده را اجرا کن.

### مجوز اجرا
فقط این عبارات مجوز اجرا محسوب می‌شوند:
- «تأیید است، اجرا کن»
- «انجامش بده»

### Git Protocol
قبل از هر Commit باید گزارش شود:
- `git status --short`
- `git diff --stat`
- `git diff`

### ممنوع
بدون تأیید ساجد، هیچ‌وقت اقدام اضافی انجام نده.
