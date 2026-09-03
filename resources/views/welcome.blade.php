<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtimanLeads | سیستم هوشمند مدیریت لید</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body { font-family: 'Vazirmatn', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #04BA07 0%, #028a05 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(4,186,7,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased overflow-x-hidden">

    <!-- Header -->
    <header class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/Artiman-logo.svg') }}" alt="Artiman Logo" class="h-10 w-auto">
                    <span class="text-xl font-bold text-[#404040] tracking-tight">Artiman<span class="text-[#04BA07]">Leads</span></span>
                </div>
                <nav class="hidden md:flex space-x-8 space-x-reverse">
                    <a href="#features" class="text-gray-600 hover:text-[#04BA07] font-medium transition">ویژگی‌ها</a>
                    <a href="#about" class="text-gray-600 hover:text-[#04BA07] font-medium transition">درباره ما</a>
                </nav>
                <div>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="bg-[#04BA07] text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700 transition shadow-lg shadow-green-500/20 transform hover:-translate-y-0.5">
                            ورود به پنل
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="hero-blob top-0 right-0 translate-x-1/3 -translate-y-1/4"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Text Content -->
                <div class="text-center lg:text-right order-2 lg:order-1">
                    <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight text-[#404040] mb-6 leading-tight">
                        مدیریت هوشمند <br>
                        <span class="gradient-text">فرآیند فروش و لیدها</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        با آرتیمان لیدز، تمامی مراحل بازدید، ارجاع و پیگیری پروژه‌های صنعتی و ساختمانی را در یک پلتفرم یکپارچه و قدرتمند مدیریت کنید.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="bg-[#04BA07] text-white px-8 py-3.5 rounded-xl font-bold text-lg hover:bg-green-700 transition shadow-xl shadow-green-500/20 flex items-center justify-center gap-2">
                            <span>شروع کنید</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <a href="#features" class="bg-white text-[#404040] border border-gray-200 px-8 py-3.5 rounded-xl font-bold text-lg hover:bg-gray-50 transition flex items-center justify-center">
                            بیشتر بدانید
                        </a>
                    </div>
                </div>

                <!-- Illustration -->
                <div class="relative order-1 lg:order-2 flex justify-center">
                    <div class="absolute inset-0 bg-gradient-to-tr from-green-100 to-transparent rounded-full filter blur-3xl opacity-60 transform scale-90"></div>
                    <!-- استفاده از SVG طراحی شده -->
                    <img src="{{ asset('images/dashboard-illustration.svg') }}" alt="Artiman Dashboard Preview" class="relative w-full max-w-lg drop-shadow-2xl transform hover:scale-[1.02] transition duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[#04BA07] font-bold tracking-wider uppercase text-sm">امکانات کلیدی</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-[#404040] mt-2">چرا آرتیمان لیدز؟</h2>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">ابزارهایی که فرآیند فروش شما را متحول می‌کنند و بهره‌وری تیم را افزایش می‌دهند.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition duration-300 border border-gray-100 hover:border-green-200">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 text-[#04BA07] group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#404040] mb-3">ثبت سریع و دقیق</h3>
                    <p class="text-gray-600 leading-relaxed">ثبت اطلاعات پروژه، بازدید میدانی و مخاطبین کلیدی در کمتر از چند دقیقه با فرم‌های هوشمند و اعتبارسنجی شده.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition duration-300 border border-gray-100 hover:border-green-200">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 text-[#04BA07] group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#404040] mb-3">ارجاع هوشمند</h3>
                    <p class="text-gray-600 leading-relaxed">سیستم ارجاع خودکار به کارشناسان فروش، ثبت تاریخچه تماس‌ها و پیگیری لحظه‌ای وضعیت هر پرونده تا مرحله نهایی.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition duration-300 border border-gray-100 hover:border-green-200">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 text-[#04BA07] group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#404040] mb-3">داشبورد تحلیلی</h3>
                    <p class="text-gray-600 leading-relaxed">مشاهده آمار لحظه‌ای، نمودارهای عملکرد تیم فروش و نرخ تبدیل لیدها برای تصمیم‌گیری‌های استراتژیک.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#404040] text-white py-12 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                <img src="{{ asset('images/Artiman-logo.svg') }}" alt="Logo" class="h-8 brightness-0 invert">
                <span class="font-bold text-lg">ArtimanSanat</span>
            </div>
            <p class="opacity-75 text-sm">© 2026 تمامی حقوق برای شرکت آرتیمان صنعت محفوظ است.</p>
        </div>
    </footer>

</body>
</html>