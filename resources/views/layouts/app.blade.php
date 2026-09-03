<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- اسکریپت حیاتی برای جلوگیری از پرش تصویر هنگام لود دارک مود -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ config('app.name', 'ArtimanLeads') }}</title>

    <!-- Fonts - Vazirmatn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // فعال‌سازی استراتژی کلاس برای دارک مود
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'sans-serif'],
                    },
                    colors: {
                        brand: '#04BA07',
                    }
                }
            }
        }
    </script>

    <!-- jalaali-js برای تبدیل تاریخ (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>

    <style>
        * { font-family: 'Vazirmatn', sans-serif !important; }
        
        /* استایل‌های پایه با پشتیبانی دارک مود */
        body { background-color: #f3f4f6; transition: background-color 0.3s ease; }
        .dark body { background-color: #111827; color: #f3f4f6; }

        .btn-primary {
            background-color: #16a34a; color: #ffffff; padding: 0.75rem 1.5rem;
            border-radius: 1rem; font-weight: 700; transition: all 0.3s ease;
        }
        .btn-primary:hover { background-color: #15803d; }

        .error-container {
            background-color: #fef2f2; border: 1px solid #ef4444; color: #dc2626;
            padding: 1rem 1.25rem; border-radius: 1rem; margin-bottom: 1.5rem;
        }
        .form-box {
            background-color: #ffffff; border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 2rem;
        }
        .dark .form-box { background-color: #1f2937; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); }

        .section-title {
            font-size: 1.5rem; font-weight: 700; color: #1f2937;
            border-bottom: 4px solid #22c55e; padding-bottom: 0.75rem; margin-bottom: 1.5rem;
        }
        .dark .section-title { color: #f3f4f6; }

        .score-display { font-size: 1.875rem; font-weight: 700; color: #16a34a; }
        .score-display.high { color: #dc2626; }
        .score-display.medium { color: #ca8a04; }
        .score-display.low { color: #6b7280; }

        .key-person-box { border: 1px solid #e5e7eb; border-radius: 1rem; padding: 1rem; }
        .dark .key-person-box { border-color: #374151; background-color: #1f2937; }
        
        .key-person-box .fields { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem; }
        .key-person-box .fields.hidden { display: none; }

        .table-container {
            background-color: #ffffff; border-radius: 1.5rem; overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        .dark .table-container { background-color: #1f2937; }
        
        .table-header { background-color: #f9fafb; }
        .dark .table-header { background-color: #111827; }
        
        .table-header th {
            padding: 1rem 1.5rem; text-align: right; font-size: 0.75rem;
            font-weight: 500; color: #6b7280; text-transform: uppercase;
        }
        .dark .table-header th { color: #9ca3af; }

        .modal-overlay {
            position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.7);
            display: none; align-items: center; justify-content: center; z-index: 50;
        }
        .modal-overlay.active { display: flex; }
        
        .modal-box {
            background-color: #ffffff; border-radius: 1.5rem; padding: 2rem;
            width: 100%; max-width: 28rem;
        }
        .dark .modal-box { background-color: #1f2937; border: 1px solid #374151; }

        @media (max-width: 768px) {
            .key-person-box .fields { grid-template-columns: 1fr; }
        }

        /* نوتیفیکیشن و دراپ‌داون */
        .notification-badge {
            position: absolute; top: -5px; right: -5px; background-color: #ef4444;
            color: white; font-size: 10px; font-weight: bold; padding: 2px 6px;
            border-radius: 50%; min-width: 18px; text-align: center;
        }
        .dropdown-menu {
            position: absolute; right: 0; top: 100%; margin-top: 0.5rem; width: 16rem;
            background: white; border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb; z-index: 50; max-height: 24rem; overflow-y: auto;
        }
        .dark .dropdown-menu { background: #1f2937; border-color: #374151; }
        .dropdown-menu.hidden { display: none !important; }
        
        .dropdown-item {
            display: block; padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }
        .dark .dropdown-item { border-bottom-color: #374151; }
        .dropdown-item:hover { background-color: #f9fafb; }
        .dark .dropdown-item:hover { background-color: #374151; }
        
        .dropdown-item.unread { background-color: #eff6ff; }
        .dark .dropdown-item.unread { background-color: #1e3a8a; }
        
        .dropdown-item .title { font-weight: 500; font-size: 0.875rem; color: #1f2937; }
        .dark .dropdown-item .title { color: #f3f4f6; }
        
        .dropdown-item .message { font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; }
        .dark .dropdown-item .message { color: #9ca3af; }
        
        .dropdown-item .time { font-size: 0.625rem; color: #9ca3af; margin-top: 0.25rem; }
        .dark .dropdown-item .time { color: #6b7280; }
        
        .dropdown-footer { padding: 0.5rem 1rem; text-align: center; border-top: 1px solid #e5e7eb; }
        .dark .dropdown-footer { border-top-color: #374151; }
        
        .dropdown-footer a { font-size: 0.75rem; color: #3b82f6; text-decoration: none; }
        .dropdown-footer a:hover { text-decoration: underline; }
        
        .notification-icon {
            position: relative; cursor: pointer; padding: 0.5rem; color: #9ca3af;
            transition: color 0.2s;
        }
        .notification-icon:hover { color: #4b5563; }
        .dark .notification-icon:hover { color: #d1d5db; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- اسکریپت سراسری برای کنترل دارک مود -->
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>