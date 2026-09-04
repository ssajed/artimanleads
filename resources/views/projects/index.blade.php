@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">📋 لیست پروژه‌ها / لیدها</h1>
        <div class="flex gap-3 flex-wrap">
            @if(auth()->user()->role === 'marketer')
                <a href="{{ route('projects.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition {{ !request()->has('filter') ? 'ring-2 ring-blue-300' : '' }}">
                    لیدهای من
                </a>
            @endif
            @if(auth()->user()->role === 'sales_expert')
                <a href="{{ route('projects.index', ['filter' => 'assigned']) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition {{ request('filter') == 'assigned' ? 'ring-2 ring-purple-300' : '' }}">
                    پروژه‌های محول شده
                </a>
            @endif
            <a href="{{ route('projects.select-type') }}" class="bg-[#04BA07] hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-medium transition shadow-md shadow-green-500/20">
                + ثبت بازدید جدید
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-2xl mb-6 transition-colors">
            {{ session('success') }}
        </div>
    @endif

    <!-- Role Info Messages -->
    @if(auth()->user()->role === 'marketer')
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-400 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-2xl mb-4 transition-colors">
            📌 شما فقط لیدهای اختصاصی خود را مشاهده می‌کنید.
        </div>
    @endif
    @if(auth()->user()->role === 'sales_expert')
        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-400 dark:border-purple-800 text-purple-800 dark:text-purple-300 px-4 py-3 rounded-2xl mb-4 transition-colors">
            📌 شما فقط پروژه‌های محول شده به خود را مشاهده می‌کنید.
        </div>
    @endif

    {{-- Active Filters Display --}}
    @if(isset($filter) && !empty($filter))
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-2xl mb-6 flex justify-between items-center flex-wrap gap-2 transition-colors">
            <div>
                <span class="font-bold dark:text-blue-200">فیلتر فعال:</span>
                @if(isset($filter['level']))
                    <span class="bg-blue-200 dark:bg-blue-800 text-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm ml-2 inline-flex items-center">
                        سطح: {{ $filter['level'] == 'A' ? ' داغ' : ($filter['level'] == 'B' ? '⏳ پیگیری' : '🗄️ آرشیو') }}
                    </span>
                @endif
                @if(isset($filter['user_id']))
                    <span class="bg-blue-200 dark:bg-blue-800 text-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm ml-2 inline-flex items-center">
                        کاربر: {{ \App\Models\User::find($filter['user_id'])?->name ?? 'نامشخص' }}
                    </span>
                @endif
            </div>
            <a href="{{ route('projects.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 text-sm font-medium transition">
                حذف فیلتر ✕
            </a>
        </div>
    @endif

    <!-- Total Count Badge -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 rounded-2xl mb-6 shadow-sm transition-colors">
        <span class="font-bold text-gray-800 dark:text-gray-200">تعداد کل پروژه‌ها: {{ $projects->total() }}</span>
    </div>

    @if($projects->count() > 0)
    <!-- Table Container -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام پروژه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">بازاریاب</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ بازدید</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">منطقه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">سطح</th>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت ارجاع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ارجاع به</th>
                        @endif
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $project->title }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $project->address ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ $project->marketer_name ?? $project->user?->name ?? 'نامشخص' }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            @if($project->visit_date)
                                {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($project->visit_date))->format('Y/m/d') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">{{ $project->region ?? '-' }}</td>
                        <td class="px-6 py-5">
                            @php
                                // نگاشت سطوح با پشتیبانی دارک مود
                                $levelMap = [
                                    'A_hot' => ['bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300', '🔥 داغ'],
                                    'B_followup' => ['bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300', ' پیگیری'],
                                    'C_archive' => ['bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300', '🗄️ آرشیو']
                                ];
                                $levelInfo = $levelMap[$project->level] ?? ['bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300', $project->level ?? '-'];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelInfo[0] }}">
                                {{ $levelInfo[1] }}
                            </span>
                        </td>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                        <td class="px-6 py-5 text-sm">
                            @php
                                $statusMap = [
                                    'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                    'accepted' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                    'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                ];
                                $statusLabels = [
                                    'pending' => 'در انتظار',
                                    'accepted' => 'پذیرفته شده',
                                    'rejected' => 'رد شده',
                                    'completed' => 'انجام شده'
                                ];
                            @endphp
                            @if($project->latestAssignment)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusMap[$project->latestAssignment->status] ?? 'bg-gray-100 dark:bg-gray-700' }}">
                                    {{ $statusLabels[$project->latestAssignment->status] ?? $project->latestAssignment->status }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 text-xs">ارجاع نشده</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ $project->latestAssignment?->assignedTo?->name ?? '-' }}
                        </td>
                        @endif
                        <td class="px-6 py-5 text-sm whitespace-nowrap">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline ml-3 transition">جزئیات</a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                                <a href="{{ route('projects.edit', $project) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 hover:underline ml-3 transition">ویرایش</a>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('آیا از حذف پروژه «{{ $project->title }}» مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:underline transition">حذف</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 transition-colors">
            {{ $projects->links() }}
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 transition-colors">
        <span class="text-6xl block mb-4">📭</span>
        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-300 mt-4">
            @if(auth()->user()->role === 'marketer')
                هیچ لیدی برای شما ثبت نشده است.
            @elseif(auth()->user()->role === 'sales_expert')
                هیچ پروژه‌ای به شما محول نشده است.
            @else
                هیچ پروژه‌ای با این فیلتر یافت نشد.
            @endif
        </h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2">فیلتر را حذف کنید یا پروژه جدید ثبت کنید.</p>
        <a href="{{ route('projects.index') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-medium transition shadow-md">
            حذف فیلتر
        </a>
    </div>
    @endif
</div>
@endsection