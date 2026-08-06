@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">مدیریت بکاپ</h1>
        <form action="{{ route('backup.create') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                📥 گرفتن بکاپ جدید
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right">نام فایل</th>
                    <th class="px-6 py-3 text-right">حجم</th>
                    <th class="px-6 py-3 text-right">تاریخ</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $backup['name'] }}</td>
                    <td class="px-6 py-4">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                    <td class="px-6 py-4">{{ date('Y-m-d H:i:s', $backup['date']) }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('backup.download', $backup['name']) }}" class="text-blue-600 hover:underline ml-2">دانلود</a>
                        
                        <form action="{{ route('backup.restore', $backup['name']) }}" method="POST" class="inline-block ml-2" 
                              onsubmit="return confirm('آیا از بازیابی این بکاپ مطمئن هستید؟ تمام داده‌های فعلی جایگزین می‌شوند.')">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:underline">بازیابی</button>
                        </form>
                        
                        <form action="{{ route('backup.delete', $backup['name']) }}" method="POST" class="inline-block ml-2" 
                              onsubmit="return confirm('آیا از حذف این فایل بکاپ مطمئن هستید؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">هیچ فایل بکاپی وجود ندارد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
        <p class="text-sm text-yellow-800">
            ⚠️ <strong>توجه:</strong> بازیابی بکاپ تمام داده‌های فعلی را جایگزین می‌کند. قبل از بازیابی، از داده‌های فعلی بکاپ بگیرید.
        </p>
    </div>
</div>
@endsection