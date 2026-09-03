@extends('layouts.app')

@section('content')
<!-- اسکریپت فعال‌سازی دارک مود -->
<script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-12 font-[Vazirmatn]">
    
    <!-- دکمه شناور تغییر تم -->
    <button onclick="toggleDarkMode()" class="fixed bottom-6 left-6 z-50 p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-yellow-400 hover:scale-110 transition-transform focus:outline-none" aria-label="تغییر حالت شب و روز">
        <svg id="sunIcon" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <svg id="moonIcon" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
    </button>

    <!-- هدر ساده داشبورد (فقط عنوان و تاریخ) -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">داشبورد مدیریت</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    آنلاین | {{ \Morilog\Jalali\Jalalian::now()->format('%d %B %Y') }}
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- کارت‌های آمار (قابل کلیک) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php $listUrl = route('projects.index'); @endphp
            
            <!-- کارت 1: کل لیدها -->
            <a href="{{ $listUrl }}" class="block bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">کل لیدهای ثبت شده</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $allLeadsCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
            </a>

            <!-- کارت 2: لیدهای داغ -->
            <a href="{{ $listUrl }}?level=A" class="block bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-green-200 dark:hover:border-green-800 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">لیدهای داغ (A)</p>
                        <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $wonLeadsCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </a>

            <!-- کارت 3: در دست پیگیری -->
            <a href="{{ $listUrl }}?level=B" class="block bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-yellow-200 dark:hover:border-yellow-800 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">در دست پیگیری (B)</p>
                        <h3 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $levelBLeadsCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </a>

            <!-- کارت 4: بایگانی شده -->
            <a href="{{ $listUrl }}?level=C" class="block bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">بایگانی شده (C)</p>
                        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $levelCLeadsCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- بخش اصلی: نمودار و فعالیت‌ها -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- نمودار -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-6">تحلیل وضعیت خرید پروژه‌ها</h3>
                <div class="relative h-72 w-full">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <!-- تایم‌لاین فعالیت‌ها -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-6">آخرین فعالیت‌ها</h3>
                <div class="space-y-5 relative">
                    @php
                        $recentProjects = \App\Models\Project::latest()->take(5)->get();
                    @endphp
                    @forelse($recentProjects as $project)
                        <div class="flex gap-4 group cursor-pointer" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 ring-4 ring-green-50 dark:ring-green-900/30 z-10"></div>
                                @if(!$loop->last)
                                    <div class="w-0.5 h-full bg-gray-100 dark:bg-gray-700 -my-1"></div>
                                @endif
                            </div>
                            
                            <div class="pb-2 flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 line-clamp-1">{{ $project->title }}</h4>
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 font-mono">{{ $project->level }}</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ \Morilog\Jalali\Jalalian::fromCarbon($project->created_at)->format('d F - H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 dark:bg-gray-700/50 rounded-lg">هنوز فعالیتی ثبت نشده است.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- اسکریپت‌ها -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // منطق دارک مود
    function toggleDarkMode() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        updateChartTheme();
    }

    // تنظیمات اولیه نمودار
    const ctx = document.getElementById('mainChart').getContext('2d');
    const chartData = @json($chartData);

    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(4, 186, 7, 0.6)');
    gradient.addColorStop(1, 'rgba(4, 186, 7, 0.0)');

    const isDarkInit = document.documentElement.classList.contains('dark');
    
    const mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'تعداد',
                data: chartData.data,
                backgroundColor: gradient,
                borderColor: '#04BA07',
                borderWidth: 2,
                pointBackgroundColor: isDarkInit ? '#1f2937' : '#fff',
                pointBorderColor: '#04BA07',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkInit ? '#1f2937' : '#404040',
                    titleFont: { family: 'Vazirmatn' },
                    bodyFont: { family: 'Vazirmatn' },
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: isDarkInit ? '#374151' : '#f3f4f6', borderDash: [5, 5] },
                    ticks: { color: isDarkInit ? '#9ca3af' : '#6b7280', font: { family: 'Vazirmatn', size: 11 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: isDarkInit ? '#9ca3af' : '#6b7280', font: { family: 'Vazirmatn', size: 11 } }
                }
            }
        }
    });

    // تابع آپدیت واقعی نمودار هنگام تغییر تم
    function updateChartTheme() {
        const isDarkNow = document.documentElement.classList.contains('dark');
        
        // تغییر رنگ گریدها و متن محورها
        mainChart.options.scales.y.grid.color = isDarkNow ? '#374151' : '#f3f4f6';
        mainChart.options.scales.y.ticks.color = isDarkNow ? '#9ca3af' : '#6b7280';
        mainChart.options.scales.x.ticks.color = isDarkNow ? '#9ca3af' : '#6b7280';
        
        // تغییر رنگ تولتیپ و نقاط نمودار
        mainChart.options.plugins.tooltip.backgroundColor = isDarkNow ? '#1f2937' : '#404040';
        mainChart.data.datasets[0].pointBackgroundColor = isDarkNow ? '#1f2937' : '#fff';
        
        mainChart.update();
    }
</script>
@endsection