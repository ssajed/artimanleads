@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- فرم ویرایش پروفایل -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">اطلاعات شخصی</h3>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">نام <span class="text-red-500">*</span></label>
                            <input id="first_name" name="first_name" type="text" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('first_name') border-red-500 @enderror" 
                                   value="{{ old('first_name', $user->first_name) }}" required autofocus>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">نام خانوادگی <span class="text-red-500">*</span></label>
                            <input id="last_name" name="last_name" type="text" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('last_name') border-red-500 @enderror" 
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">شماره موبایل</label>
                        <input id="mobile" name="mobile" type="text" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('mobile') border-red-500 @enderror" 
                               value="{{ old('mobile', $user->mobile) }}">
                        @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">ایمیل <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">نقش کاربری</label>
                        <div class="mt-1 block w-full px-4 py-3 bg-gray-100 rounded-md border">
                            @php
                                $roles = [
                                    'admin' => 'مدیر کل',
                                    'sales_manager' => 'مدیر فروش',
                                    'sales_expert' => 'کارشناس فروش',
                                    'marketer' => 'بازاریاب'
                                ];
                            @endphp
                            <span class="font-medium">{{ $roles[$user->role] ?? $user->role }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            💾 ذخیره تغییرات
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600">✅ پروفایل با موفقیت به‌روزرسانی شد.</p>
                        @endif
                        @if (session('error'))
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- بخش مدیریت کاربران (فقط برای مدیر کل) -->
        @if(auth()->user()->role === 'admin')
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-blue-700 mb-6">👥 مدیریت کاربران و دسترسی‌ها</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ایمیل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موبایل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نقش فعلی</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تغییر نقش</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $u)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium">{{ $u->first_name }} {{ $u->last_name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $u->email }}</td>
                                <td class="px-6 py-4 text-sm">{{ $u->mobile ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $roleLabels = [
                                            'admin' => 'مدیر کل',
                                            'sales_manager' => 'مدیر فروش',
                                            'sales_expert' => 'کارشناس فروش',
                                            'marketer' => 'بازاریاب'
                                        ];
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'sales_manager' => 'bg-blue-100 text-blue-800',
                                            'sales_expert' => 'bg-green-100 text-green-800',
                                            'marketer' => 'bg-gray-100 text-gray-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $roleColors[$u->role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $roleLabels[$u->role] ?? $u->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <form action="{{ route('profile.update-role') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <select name="role" class="rounded-md border-gray-300 shadow-sm text-sm">
                                            <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>مدیر کل</option>
                                            <option value="sales_manager" {{ $u->role == 'sales_manager' ? 'selected' : '' }}>مدیر فروش</option>
                                            <option value="sales_expert" {{ $u->role == 'sales_expert' ? 'selected' : '' }}>کارشناس فروش</option>
                                            <option value="marketer" {{ $u->role == 'marketer' ? 'selected' : '' }}>بازاریاب</option>
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs transition">
                                            اعمال
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- بخش حذف حساب کاربری (فقط برای مدیر کل) -->
        @if(auth()->user()->role === 'admin')
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-t-4 border-red-500">
                <h3 class="text-lg font-bold text-red-700 mb-6">⚠️ حذف حساب کاربری</h3>
                
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <p class="text-sm text-gray-600">
                        پس از حذف حساب کاربری، تمام داده‌های شما پاک خواهد شد.
                    </p>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">رمز عبور</label>
                        <input id="password" name="password" type="password" 
                               class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 @error('password', 'userDeletion') border-red-500 @enderror" 
                               placeholder="برای تایید رمز عبور خود را وارد کنید">
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        🗑️ حذف حساب کاربری
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection