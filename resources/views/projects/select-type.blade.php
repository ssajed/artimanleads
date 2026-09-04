@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">انتخاب نوع بازدید</h1>
            <p class="text-gray-600 dark:text-gray-400">لطفاً نوع پروژه مورد نظر خود را برای ثبت لید جدید انتخاب کنید.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Building Project -->
            <a href="{{ route('projects.create', ['type' => 'building']) }}" 
               class="group block bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-[#04BA07] dark:hover:border-green-500 transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10 text-[#04BA07]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">پروژه ساختمانی</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">ثبت بازدید برای ساختمان‌های مسکونی، اداری و تجاری</p>
            </a>

            <!-- Industrial Project -->
            <a href="{{ route('projects.create', ['type' => 'industrial']) }}" 
               class="group block bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">پروژه صنعتی</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">ثبت بازدید برای کارخانجات، سوله‌ها و تاسیسات صنعتی</p>
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                بازگشت به لیست لیدها
            </a>
        </div>
    </div>
</div>
@endsection