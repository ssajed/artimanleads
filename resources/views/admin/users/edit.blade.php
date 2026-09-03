@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">ویرایش کاربر: {{ $user->name }}</h2>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام کامل</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        </div>

                        <!-- موبایل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">شماره موبایل</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- نقش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نقش کاربری</label>
                            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @foreach(\App\Models\User::getRoles() as $key => $label)
                                    <option value="{{ $key }}" {{ $user->role == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                    <!-- وضعیت فعال بودن -->
                        <div>
    <label class="block text-sm font-medium text-gray-700">وضعیت</label>
    <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        <option value="1" {{ $user->is_active ? 'selected' : '' }}>فعال</option>
        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>غیرفعال</option>
    </select>
</div>
                        
                         <!-- پسورد جدید (اختیاری) -->
                         <div>
                            <label class="block text-sm font-medium text-gray-700">تغییر پسورد (اختیاری)</label>
                            <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <p class="text-xs text-gray-500 mt-1">اگر نمی‌خواهید پسورد تغییر کند، خالی بگذارید.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">انصراف</a>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection