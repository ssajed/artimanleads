@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Back Button -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                📞 جزئیات تماس
            </h1>
            <a href="{{ route('call-logs.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#04BA07] dark:hover:text-green-400 transition font-medium">
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                بازگشت به لیست
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-xl mb-6 transition-colors">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Details Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors mb-6">
            <div class="p-8">
                
                <!-- Project Info Section -->
                <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">پروژه مرتبط</h3>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $callLog->project?->title ?? 'پروژه حذف شده' }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $callLog->project?->region ?? '-' }}</p>
                            @if($callLog->project)
                                <a href="{{ route('projects.show', $callLog->project) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">مشاهده جزئیات پروژه →</a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact & Call Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">اطلاعات مخاطب</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">نام:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $callLog->contact_name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">سمت:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $callLog->contact_position ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">شماره تماس:</span>
                                <span class="font-medium text-gray-900 dark:text-white dir-ltr">{{ $callLog->contact_phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">اطلاعات تماس</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">تاریخ و ساعت:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($callLog->created_at)->format('Y/m/d - H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">ثبت کننده:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $callLog->user?->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">نوع تماس:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $callLog->call_type ?? 'عمومی' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Box -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">خلاصه مکالمه</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">
                        {{ $callLog->summary ?? 'خلاصه‌ای ثبت نشده است.' }}
                    </div>
                </div>

                <!-- Next Step / Notes -->
                @if($callLog->next_step || $callLog->notes)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-5 rounded-xl border border-yellow-200 dark:border-yellow-800">
                    <h3 class="text-sm font-bold text-yellow-800 dark:text-yellow-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        اقدام بعدی / یادداشت
                    </h3>
                    <p class="text-yellow-900 dark:text-yellow-200 text-sm leading-relaxed">
                        {{ $callLog->next_step ?? $callLog->notes ?? '-' }}
                    </p>
                </div>
                @endif

            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-50 dark:bg-gray-700/30 px-8 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                @if(auth()->user()->role === 'admin' || auth()->id() === $callLog->user_id)
                    <a href="{{ route('call-logs.edit', $callLog) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 font-medium transition">
                        ویرایش
                    </a>
                @endif
                <button onclick="window.print()" class="px-5 py-2.5 rounded-lg bg-gray-800 dark:bg-gray-600 text-white hover:bg-gray-700 dark:hover:bg-gray-500 font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    چاپ
                </button>
            </div>
        </div>
    </div>
</div>
@endsection