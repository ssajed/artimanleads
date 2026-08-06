# وضعیت فعلی پروژه ArtimanLeads

**تاریخ:** 2026-08-06
**فاز:** Stabilization (تثبیت زیرساخت)

---

## 🎯 وظیفه جاری

### **عنوان:** اصلاح لود CSS Dashboard با Vite

**وضعیت:** ✅ انجام شد

**تغییرات اعمال‌شده:**
- اضافه کردن `@vite(['resources/css/app.css', 'resources/js/app.js'])` به `resources/views/layouts/app.blade.php`

**نتیجه:**
- Dashboard با استایل صحیح نمایش داده می‌شود.
- خطای `ERR_CONNECTION_RESET` مربوط به `cdn.tailwindcss.com` برطرف شده است.

---

## 📊 وضعیت ماژول‌ها

| ماژول | وضعیت | مشکل |
|-------|-------|------|
| **Dashboard** | ✅ درست کار می‌کند | - |
| **Navigation** | ⚠️ نیاز به اصلاح | Route `backup.index` نامعتبر است |
| **Project** | ✅ درست کار می‌کند | - |
| **Assignment** | ✅ درست کار می‌کند | - |
| **Call Log** | ✅ درست کار می‌کند | - |
| **User Management** | ✅ درست کار می‌کند | - |
| **Backup** | ✅ درست کار می‌کند | Route Navigation باید اصلاح شود |

---

## 📋 برنامه مرحله بعدی

### **اولویت بعدی (P1): اصلاح مسیر Backup در Navigation**

**مشکل:**
- در `resources/views/layouts/navigation.blade.php` از `route('backup.index')` استفاده شده است.
- Route جدید `admin.backup` در `routes/web.php` تعریف شده است.

**اقدام مورد نیاز:**
- تغییر `route('backup.index')` به `route('admin.backup')` در `navigation.blade.php`
- بررسی اینکه لینک فقط برای کاربران Admin نمایش داده شود.

---

## 📝 قوانین توسعه

1. قبل از هر تغییر، `docs/AI_CONTEXT.md` و `docs/CURRENT_TASK.md` خوانده شوند.
2. تغییرات فقط برای Task جاری انجام شود.
3. بعد از هر تغییر، `CURRENT_TASK.md` به‌روزرسانی شود.
4. هیچ دستور `npm` روی سرور اجرا نشود.
5. فایل‌های `public/build/` حذف نشوند.
6. قبل از تغییرات مخرب (مثل `rm`)، توقف و تأیید گرفته شود.

---

## 📎 اطلاعات پروژه

- **مسیر سرور:** `/home/cp36529/public_html/artimanleads`
- **نسخه Laravel:** 11
- **نسخه PHP:** 8.2
- **محیط:** cPanel (Production)
- **Branch:** master
