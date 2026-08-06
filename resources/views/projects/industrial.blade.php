@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-yellow-50 border border-yellow-200 rounded-3xl p-8 text-center">
        <div class="text-6xl mb-4">🏭</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">در حال طراحی</h1>
        <p class="text-gray-600 mb-6">فرم ثبت پروژه‌های صنعتی در حال طراحی و توسعه است.</p>
        <div class="flex flex-col items-center gap-4">
            <div class="w-full max-w-md bg-gray-200 rounded-full h-2.5">
                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 45%"></div>
            </div>
            <p class="text-sm text-gray-500">پیشرفت توسعه: ۴۵%</p>
            <a href="{{ route('projects.create', ['type' => 'building']) }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-medium transition">
                🔙 بازگشت به ثبت پروژه ساختمانی
            </a>
        </div>
    </div>
</div>
@endsection