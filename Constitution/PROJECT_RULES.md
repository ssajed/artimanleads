# قانون اساسی پروژه CRM آرتیمان

**نسخه:** 1.1  
**تاریخ:** 2026-08-08  
**وضعیت:** ACTIVE

---

# 1. ساختار تیم

## کارفرما
کارفرما تصمیم نهایی پروژه را می‌گیرد.

## مشاور پروژه
ChatGPT

وظایف:
- تحلیل نیازمندی‌ها
- طراحی معماری
- بررسی کد
- تحلیل خطاها
- تعیین راه‌حل
- بررسی ریسک تغییرات
- تعیین ترتیب اجرای فازها
- بررسی نتیجه کار برنامه‌نویس

## برنامه‌نویس
Qwen

وظایف:
- اجرای دستورات فنی
- کدنویسی
- تست
- رفع خطا
- اجرای دستورات سرور
- گزارش دقیق نتیجه
- عدم تغییر خودسرانه معماری

---

# 2. محیط‌های پروژه

## TEST

URL:

https://test.artimansanat.com/

Path:

~/public_html/leadtest

Database:

cp36529_artimanleads_db_test

---

## PRODUCTION

URL:

https://lead.artimansanat.com/

Path:

~/public_html/artimanleads

Database:

cp36529_artimanleads_db

---

# 3. قانون طلایی Production

هرگز بدون تأیید کارفرما روی Production تغییر ایجاد نشود.

ترتیب استاندارد:

Analysis
→ Plan
→ Development
→ Test
→ Verification
→ Approval
→ Production

---

# 4. Test First

هر قابلیت جدید ابتدا در محیط Test ساخته و آزمایش شود.

تا زمانی که قابلیت در Test تأیید نشده است:

- به Production منتقل نشود.
- Migration آن روی Production اجرا نشود.
- Database Production تغییر نکند.

---

# 5. Database Safety

Database Production:

cp36529_artimanleads_db

Database Test:

cp36529_artimanleads_db_test

هرگز بدون تأیید از دستورات مخرب روی Production استفاده نشود:

- DROP DATABASE
- DROP TABLE
- TRUNCATE
- DELETE گسترده
- migrate:fresh
- migrate:refresh
- db:wipe

قبل از تغییرات مهم Database باید Backup گرفته شود.

---

# 6. Backup

قبل از تغییرات پرریسک Backup الزامی است.

موارد پرریسک:

- Database
- Migration
- Authentication
- Session
- Middleware
- Models
- Controllers
- Config
- Routes

حداقل:

Database Backup
+
File/Git Backup

---

# 7. Environment

Test و Production باید Database مستقل داشته باشند.

Test هرگز نباید به Database Production متصل شود.

Test:

APP_ENV=testing

Production:

APP_ENV=production

---

# 8. Laravel / PHP

Framework:

Laravel 11

PHP:

8.2

Database:

MySQL

Frontend:

Bootstrap 5

---

# 9. قانون Frontend — بسیار مهم

## Separation of Concerns

کد باید از سه بخش جدا تشکیل شود:

Backend
Frontend Structure
Frontend Style

### PHP / Laravel

مسئول:

- Business Logic
- Database
- Models
- Controllers
- Services
- Validation
- Authentication
- Authorization

### Blade

مسئول:

- HTML Structure
- نمایش داده
- استفاده از Components
- استفاده از Classes

### CSS

مسئول:

- رنگ
- اندازه
- فاصله
- Layout
- Animation
- Responsive Design
- ظاهر Components

### JavaScript

مسئول:

- تعامل کاربر
- Dynamic UI
- Events
- AJAX
- رفتارهای Frontend

---

# 10. CSS داخل PHP ممنوع

نوشتن CSS داخل فایل‌های PHP یا Blade ممنوع است.

این موارد ممنوع هستند:

<style>
...
</style>

داخل Blade.

همچنین:

style="..."

به‌صورت Inline نباید استفاده شود.

مثال ممنوع:

<button style="background:red">

مثال صحیح:

<button class="login-button">

و CSS باید در فایل CSS باشد:

.login-button {
    background: red;
}

---

# 11. CSS باید جدا باشد

CSS باید در مسیر مناسب قرار گیرد.

ساختار پیشنهادی:

resources/css/

مثلاً:

resources/css/app.css
resources/css/auth.css
resources/css/dashboard.css
resources/css/projects.css

در صورت نیاز به CSS اختصاصی برای یک Feature، فایل CSS جداگانه ساخته شود.

---

# 12. JavaScript

منطق JavaScript نباید داخل Blade نوشته شود مگر در موارد بسیار محدود و با تأیید.

منطق اصلی JavaScript باید در:

resources/js/

قرار گیرد.

مثال:

resources/js/app.js
resources/js/auth.js
resources/js/projects.js

---

# 13. Inline JavaScript ممنوع

از نوشتن منطق بزرگ JavaScript داخل:

<script>
...
</script>

در Blade خودداری شود.

همچنین استفاده گسترده از:

onclick=""
onchange=""
onsubmit=""

ممنوع است.

Eventها ترجیحاً در فایل JavaScript مدیریت شوند.

---

# 14. Blade

Blade باید تمیز و قابل خواندن باشد.

Blade نباید تبدیل به محل نوشتن:

- CSS
- JavaScript بزرگ
- Business Logic
- Query
- منطق پیچیده PHP

شود.

---

# 15. Business Logic

Business Logic باید در Backend مناسب قرار گیرد.

ترجیح:

Controller
Service
Model

نه داخل Blade.

---

# 16. تغییر کد

قبل از تغییر هر فایل:

1. فایل بررسی شود.
2. ساختار موجود خوانده شود.
3. وابستگی‌ها بررسی شود.
4. حداقل تغییر لازم مشخص شود.
5. سپس تغییر انجام شود.

از بازنویسی کامل فایل بدون ضرورت خودداری شود.

---

# 17. حدس ممنوع

هیچ‌کس نباید بدون بررسی بگوید:

"حل شد"

یا:

"100 درصد درست شد"

مگر اینکه تست واقعی انجام شده باشد.

گزارش باید شامل:

Command
Result
Test
Final Status

باشد.

---

# 18. Error Fixing

برای هر خطا:

1. Error Message خوانده شود.
2. Stack Trace بررسی شود.
3. File مشخص شود.
4. Line مشخص شود.
5. علت واقعی پیدا شود.
6. حداقل Fix انجام شود.
7. Syntax بررسی شود.
8. Cache در صورت نیاز پاک شود.
9. همان سناریو دوباره تست شود.

---

# 19. تست

بعد از تغییرات مناسب، تست انجام شود.

Syntax:

php -l filename.php

Laravel:

php artisan optimize:clear

Routes:

php artisan route:list

Database:

بررسی Schema و Query واقعی.

UI:

Browser Test

---

# 20. Authentication

Authentication، Session، Cookie و CSRF حساس هستند.

تغییرات این بخش ابتدا در Test انجام شود.

CSRF نباید برای حل سریع خطای 419 به‌صورت دائمی غیرفعال شود.

HTTPS باید در محیط واقعی استفاده شود.

---

# 21. Security

اطلاعات حساس نباید در:

- Git
- Public Files
- Debug عمومی
- Chat عمومی

قرار گیرد.

فایل:

.env

نباید Commit شود.

---

# 22. Git

قبل از تغییر:

git status

بعد از تغییر:

git diff

بررسی شود.

تغییرات غیرمرتبط نباید وارد Commit شوند.

---

# 23. Migration

Migration باید:

- مشخص
- قابل بررسی
- قابل Rollback
- تست‌شده

باشد.

Migration ابتدا روی Test اجرا شود.

سپس بعد از تأیید روی Production اجرا شود.

---

# 24. UI / Design

UI باید:

- حرفه‌ای
- مدرن
- تمیز
- Responsive
- مناسب Desktop
- مناسب Mobile

باشد.

Login فعلی پروژه به‌عنوان یکی از الگوهای طراحی پروژه در نظر گرفته شود.

طراحی صفحات جدید باید تا حد امکان با Design System موجود هماهنگ باشد.

---

# 25. فایل‌های Frontend

اصل کلی:

HTML → Blade

CSS → resources/css

JavaScript → resources/js

PHP → app/

Assets → resources/

از مخلوط کردن مسئولیت‌ها خودداری شود.

---

# 26. Overengineering ممنوع

برای مشکل ساده راه‌حل پیچیده ایجاد نشود.

بدون نیاز:

- Package جدید نصب نشود.
- Service جدید ایجاد نشود.
- Repository جدید ایجاد نشود.
- Abstraction غیرضروری ایجاد نشود.
- معماری تغییر نکند.

---

# 27. Production Protection

Production محیط واقعی شرکت است.

هیچ تستی نباید روی Production انجام شود.

برای تست از Test استفاده شود.

Production فقط برای:

- Deployment تأییدشده
- Migration تأییدشده
- Bug Fix تأییدشده
- عملیات ضروری

استفاده شود.

---

# 28. Workflow

Workflow رسمی پروژه:

کارفرما
↓
ChatGPT
↓
Qwen
↓
Test
↓
Verification
↓
کارفرما
↓
Production

---

# 29. مراحل توسعه

هر Feature:

Analysis
↓
Plan
↓
Implementation
↓
Test
↓
Review
↓
Approval
↓
Deploy

---

# 30. قانون تغییرات کوچک

اصل مهم:

"Smallest Effective Change"

یعنی:

کوچک‌ترین تغییر مؤثر را انجام بده.

اگر مشکل با تغییر یک فایل حل می‌شود، چندین فایل تغییر داده نشود.

---

# 31. گزارش Qwen

پس از اجرای هر دستور، نتیجه واقعی گزارش شود.

نمونه:

Command:
php artisan optimize:clear

Result:
DONE

Test:
Login tested successfully.

Status:
PASS

در صورت خطا:

Command:
...

Result:
ERROR

Error:
...

Next Step:
...

---

# 32. قانون نهایی

سه اصل غیرقابل مذاکره:

1. Production نباید آسیب ببیند.

2. هر تغییر مهم ابتدا در Test انجام شود.

3. هیچ‌کس بدون تست واقعی ادعا نکند که مشکل حل شده است.

---

# 33. نسخه پروژه

Laravel:
11.54.0

PHP:
8.2

Test:
https://test.artimansanat.com/

Production:
https://lead.artimansanat.com/

---

# END
