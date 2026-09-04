@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">💾 مدیریت پشتیبان‌گیری</h1>
        
        <form action="{{ route('backup.create') }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟ فرآیند بکاپ‌گیری ممکن است چند دقیقه طول بکشد.')">
            @csrf
            <button type="submit" class="bg-[#04BA07] hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-green-500/20 flex items-center gap-2 w-full md:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                ایجاد بکاپ جدید
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-xl mb-6 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-300 px-5 py-4 rounded-xl mb-6 transition-colors">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام فایل</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">حجم</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ ایجاد</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($backups ?? [] as $backup)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-mono text-sm font-bold text-gray-900 dark:text-white break-all">{{ $backup['filename'] ?? $backup }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ isset($backup['size']) ? $backup['size'] : '-' }}
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">
                            {{ isset($backup['date']) ? $backup['date'] : '-' }}
                        </td>
                        <td class="px-6 py-5 text-sm whitespace-nowrap space-x-2 space-x-reverse">
                            @if(isset($backup['filename']))
                                <a href="{{ route('backup.download', $backup['filename']) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    دانلود
                                </a>
                                
                                <form action="{{ route('backup.restore', $backup['filename']) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ هشدار: دیتابیس فعلی با این بکاپ جایگزین خواهد شد. آیا مطمئن هستید؟')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 font-medium transition">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        بازیابی
                                    </button>
                                </form>
                                
                                <form action="{{ route('backup.delete', $backup['filename']) }}" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف دائمی این فایل پشتیبان مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium transition">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        حذف
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                                <p class="text-lg font-medium">هیچ فایل پشتیبانی وجود ندارد</p>
                                <p class="text-sm mt-1">برای شروع، یک بکاپ جدید ایجاد کنید.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection