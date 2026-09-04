@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white"> لیست تماس‌ها و مکاتبات</h1>
        <a href="{{ route('call-logs.select-project') }}" class="bg-[#04BA07] hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-green-500/20 flex items-center gap-2 w-full md:w-auto justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            ثبت تماس جدید
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-xl mb-6 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">پروژه مرتبط</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">مخاطب / سمت</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">خلاصه مکالمه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ثبت کننده</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ و ساعت</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($callLogs ?? [] as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $log->project?->title ?? '-' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $log->project?->region ?? '' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $log->contact_name ?? '-' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->contact_position ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-400 max-w-md truncate" title="{{ $log->summary }}">
                            {{ $log->summary ?? '-' }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ $log->user?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            {{ \Morilog\Jalali\Jalalian::fromCarbon($log->created_at)->format('Y/m/d - H:i') }}
                        </td>
                        <td class="px-6 py-5 text-sm whitespace-nowrap">
                            <a href="{{ route('call-logs.show', $log) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                مشاهده جزئیات
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <p class="text-lg font-medium">هیچ تماسی ثبت نشده است</p>
                                <p class="text-sm mt-1">برای شروع پیگیری، یک تماس جدید ثبت کنید.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($callLogs) && $callLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 transition-colors">
                {{ $callLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection