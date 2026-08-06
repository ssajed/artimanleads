@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">نوع پروژه را انتخاب کنید</h1>
        <p class="text-gray-500 mt-2">لطفاً نوع پروژه مورد نظر خود را انتخاب کنید</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('projects.create', ['type' => 'building']) }}" 
           class="block bg-white rounded-3xl shadow-xl p-8 text-center hover:shadow-2xl transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-green-500">
            <div class="text-7xl mb-4">🏗️</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">پروژه ساختمانی</h2>
            <p class="text-gray-500">ثبت لیدهای ساختمانی و مسکونی</p>
            <div class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-full text-sm font-medium">
                ثبت پروژه
            </div>
        </a>

        <a href="{{ route('projects.create', ['type' => 'industrial']) }}" 
           class="block bg-white rounded-3xl shadow-xl p-8 text-center hover:shadow-2xl transition-all duration-300 hover:scale-105 border-2 border-transparent hover:border-yellow-500">
            <div class="text-7xl mb-4">🏭</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">پروژه صنعتی</h2>
            <p class="text-gray-500">ثبت لیدهای صنعتی و کارخانه‌ای</p>
            <div class="mt-4 inline-block bg-yellow-500 text-white px-6 py-2 rounded-full text-sm font-medium">
                در حال طراحی
            </div>
        </a>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
            🔙 بازگشت به داشبورد
        </a>
    </div>
</div>
@endsection