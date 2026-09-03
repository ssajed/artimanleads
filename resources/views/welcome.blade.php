<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ArtimanLeads - سیستم مدیریت هوشمند لید</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=vazirmatn:400,500,700&display=swap" rel="stylesheet" />
        
        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Vazirmatn', sans-serif; }
            /* گرادیانت سبز سازمانی */
            .gradient-bg {
                background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">
        
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- لوگوی SVG از پوشه images -->
                    <img src="{{ asset('images/Artiman-logo.svg') }}" alt="Artiman Logo" class="h-10 w-auto">
                    <span class="font-bold text-xl text-gray-800 hidden sm:block">ArtimanLeads</span>
                </div>
                <nav class="hidden md:flex gap-6">
                    <a href="#features" class="text-gray-600 hover:text-green-600 transition font-medium">ویژگی‌ها</a>
                    <a href="#about" class="text-gray-600 hover:text-green-600 transition font-medium">درباره ما</a>
                </nav>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md font-medium">داشبورد</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-green-600 font-medium hover:bg-green-50 rounded-lg transition border border-green-200">ورود</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium">ثبت نام</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="gradient-bg text-white py-20 lg:py-32 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">مدیریت هوشمند لیدهای فروش</h1>
                <p class="text-lg md:text-xl text-green-50 mb-10 max-w-2xl mx-auto font-light">
                    با ArtimanLeads، فرآیند ثبت بازدید، پیگیری مشتریان و نهایی‌سازی قراردادها را با دقت و سرعت بالا انجام دهید.
                </p>
                <div class="flex justify-center gap-4 flex-col sm:flex-row">
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-green-700 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg transform hover:-translate-y-1">شروع رایگان</a>
                    <a href="#features" class="px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white/20 transition">بیشتر بدانید</a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900">چرا ArtimanLeads؟</h2>
                    <p class="mt-4 text-gray-600">ابزارهایی که برای رشد کسب‌وکار شما طراحی شده‌اند</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-xl hover:border-green-200 transition duration-300">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">ثبت سریع لید</h3>
                        <p class="text-gray-600 leading-relaxed">ثبت اطلاعات پروژه، افراد کلیدی و تجهیزات در کمتر از ۲ دقیقه با فرم‌های چندمرحله‌ای و هوشمند.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-xl hover:border-green-200 transition duration-300">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">داشبورد آماری</h3>
                        <p class="text-gray-600 leading-relaxed">مشاهده عملکرد بازاریاب‌ها، نرخ تبدیل لیدها و گزارش‌های ماهانه به صورت لحظه‌ای و دقیق.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-xl hover:border-green-200 transition duration-300">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">پیگیری دقیق</h3>
                        <p class="text-gray-600 leading-relaxed">ثبت تاریخچه تماس‌ها، یادآوری‌های خودکار و ارجاع هوشمند لیدها به کارشناسان فروش متخصص.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-12 border-t-4 border-green-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex justify-center mb-4">
                     <img src="{{ asset('images/Artiman-logo.svg') }}" alt="Artiman Logo" class="h-8 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition">
                </div>
                <p>&copy; {{ date('Y') }} ArtimanLeads. تمامی حقوق محفوظ است.</p>
            </div>
        </footer>

    </body>
</html>