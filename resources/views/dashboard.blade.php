@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- کارت‌های آماری -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <a href="{{ route('projects.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                <div class="text-sm text-gray-500">کل پروژه‌ها</div>
                <div class="text-2xl font-bold text-blue-600">{{ $allLeadsCount ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-1">مشاهده همه</div>
            </a>
            
            <a href="{{ route('projects.index', ['level' => 'A']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                <div class="text-sm text-gray-500">پروژه‌های داغ (A)</div>
                <div class="text-2xl font-bold text-green-600">{{ $wonLeadsCount ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-1">مشاهده پروژه‌های داغ</div>
            </a>
            
            <a href="{{ route('projects.index', ['level' => 'B']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                <div class="text-sm text-gray-500">در حال پیگیری (B)</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $levelBLeadsCount ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-1">مشاهده پروژه‌های در حال پیگیری</div>
            </a>
            
            <a href="{{ route('projects.index', ['level' => 'C']) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition cursor-pointer">
                <div class="text-sm text-gray-500">آرشیو (C)</div>
                <div class="text-2xl font-bold text-gray-600">{{ $levelCLeadsCount ?? 0 }}</div>
                <div class="text-xs text-gray-400 mt-1">مشاهده آرشیو</div>
            </a>
        </div>

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
            <!-- نمودار برای مدیران -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">وضعیت خرید پروژه‌ها</h3>
                <canvas id="leadsChart" height="100"></canvas>
            </div>

            <!-- جدول آمار بازاریاب‌ها -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">آمار بازاریاب‌ها</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام بازاریاب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تعداد لید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">درصد موفقیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($marketerStats ?? [] as $stat)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $stat['name'] }}</td>
                                <td class="px-6 py-4 text-sm">{{ $stat['leads'] }}</td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $stat['leads'] > 0 ? round(($stat['won'] / $stat['leads']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">هیچ بازاریابی ثبت نشده است.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- داشبورد شخصی برای کارشناسان و بازاریاب‌ها -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">داشبورد شخصی شما</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('projects.index', ['user_id' => auth()->id()]) }}" class="bg-blue-50 p-4 rounded-lg hover:bg-blue-100 transition cursor-pointer block">
                        <p class="text-sm text-gray-600">تعداد لیدهای شما</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['my_leads'] ?? 0 }}</p>
                        <div class="text-xs text-gray-400 mt-1">مشاهده لیدهای من</div>
                    </a>
                    <a href="{{ route('projects.index', ['level' => 'A', 'user_id' => auth()->id()]) }}" class="bg-green-50 p-4 rounded-lg hover:bg-green-100 transition cursor-pointer block">
                        <p class="text-sm text-gray-600">تعداد برنده شده</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['my_won'] ?? 0 }}</p>
                        <div class="text-xs text-gray-400 mt-1">مشاهده لیدهای برنده</div>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- اسکریپت Chart.js -->
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('leadsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels'] ?? ['بدون استعلام', 'استعلام', 'مذاکره', 'خرید شده']) !!},
            datasets: [{
                label: 'تعداد پروژه‌ها',
                data: {!! json_encode($chartData['data'] ?? [0, 0, 0, 0]) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(255, 159, 64, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endif
@endsection