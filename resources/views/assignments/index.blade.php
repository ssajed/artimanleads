@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">💾 مدیریت پشتیبان‌گیری</h1>
        <form action="{{ route('backup.create') }}" method="POST">
            @csrf
            <button type="submit" class="bg-[#04BA07] hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-medium transition shadow-md shadow-green-500/20 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                ایجاد بکاپ جدید
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-2xl mb-6 transition-colors">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">نام فایل</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">حجم</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">تاریخ ایجاد</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($backups ?? [] as $backup)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5 text-sm font-mono text-gray-700 dark:text-gray-300">{{ $backup['filename'] }}</td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">{{ $backup['size'] }}</td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">{{ $backup['date'] }}</td>
                        <td class="px-6 py-5 text-sm whitespace-nowrap">
                            <a href="{{ route('backup.download', $backup['filename']) }}" class="text-blue-600 dark:text-blue-400 hover:underline ml-4">دانلود</a>
                            
                            <form action="{{ route('backup.restore', $backup['filename']) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('آیا مطمئن هستید؟ دیتابیس فعلی جایگزین خواهد شد!')">
                                @csrf
                                <button type="submit" class="text-yellow-600 dark:text-yellow-400 hover:underline">بازیابی</button>
                            </form>
                            
                            <form action="{{ route('backup.delete', $backup['filename']) }}" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این بکاپ مطمئن هستید؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">هیچ فایل پشتیبانی وجود ندارد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection