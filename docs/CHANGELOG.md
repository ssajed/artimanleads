# چنگ‌لاگ پروژه ArtimanLeads

## [2026-08-06] - اصلاح لود CSS Dashboard
### تغییرات
- اضافه کردن `@vite(['resources/css/app.css', 'resources/js/app.js'])` به `resources/views/layouts/app.blade.php`.
- پاکسازی کش‌ها با `php artisan optimize:clear`.
- ایجاد فایل‌های Context.

### وضعیت فعلی
- Dashboard با استایل صحیح نمایش داده می‌شود.
- خطای `ERR_CONNECTION_RESET` برای `cdn.tailwindcss.com` دیگر وجود ندارد.
- `public/build/assets/app-BXE6TF6l.css` به‌درستی لود می‌شود.

### اقدامات بعدی
- اصلاح Route `backup.index` در Navigation به `admin.backup`.
- Refactor مدل `Project` برای رفع دوبارگی فیلدها.

## [قبل از 2026-08-06] - وضعیت اولیه
- پروژه از cPanel به Git منتقل شد.
- Tailwind محلی نصب و Build شد.
- مسیرهای Admin و Backup تعریف شدند.
- برخی Routeهای تستی در پروژه باقی مانده بودند.
