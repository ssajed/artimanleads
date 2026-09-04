@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">👥 مدیریت کاربران</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">نام</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ایمیل / موبایل</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">نقش</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">وضعیت</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users ?? [] as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm">
                            <div class="text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $user->mobile ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
                                    'sales_manager' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                    'sales_expert' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                                    'marketer' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300'
                                ];
                                $roleLabels = ['admin' => 'مدیر کل', 'sales_manager' => 'مدیر فروش', 'sales_expert' => 'کارشناس فروش', 'marketer' => 'بازاریاب'];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 dark:bg-gray-700' }}">
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($user->is_active)
                                <span class="text-green-600 dark:text-green-400 text-sm font-bold">✅ فعال</span>
                            @else
                                <span class="text-red-600 dark:text-red-400 text-sm font-bold"> غیرفعال</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">ویرایش</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">هیچ کاربری یافت نشد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection