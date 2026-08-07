@extends('layouts.app')


@php
    $showTypeSelector = true; // برای نمایش گزینه‌های انتخاب نوع
@endphp


@section('content')

<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-green-600 mb-8 text-center">فرم بازدید پروژه</h1>

    <form action="{{ route('projects.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl p-8" enctype="multipart/form-data">
        @csrf

        <!-- نمایش خطاهای کلی -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-500 text-red-700 px-5 py-4 rounded-2xl mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-xl">❌</span>
                    <div>
                        <p class="font-bold mb-1">لطفاً خطاهای زیر را برطرف کنید:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-2xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- بخش ۱: اطلاعات پروژه -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">اطلاعات پروژه</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">تاریخ بازدید <span class="text-red-500">*</span></label>
                    <input type="text" id="visit_date_display" value="{{ old('visit_date_shamsi') ?? \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}" onclick="showDateModal()" readonly class="w-full border rounded-2xl px-4 py-3 cursor-pointer bg-gray-50 @error('visit_date') border-red-500 @enderror">
                    <input type="hidden" name="visit_date_shamsi" id="visit_date_shamsi" value="{{ old('visit_date_shamsi') }}">
                    <input type="hidden" name="visit_date" id="visit_date_gregorian" value="{{ old('visit_date') }}">
                    @error('visit_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 font-medium">نام بازاریاب</label>
                    <input type="text" name="marketer_name" value="{{ auth()->user()->full_name ?? auth()->user()->name }}" readonly class="w-full border rounded-2xl px-4 py-3 bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block mb-2 font-medium">نام پروژه <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border rounded-2xl px-4 py-3 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">آدرس <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" required class="w-full border rounded-2xl px-4 py-3 @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 font-medium">منطقه <span class="text-red-500">*</span></label>
                    <input type="text" name="region" value="{{ old('region') }}" required class="w-full border rounded-2xl px-4 py-3 @error('region') border-red-500 @enderror">
                    @error('region')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 font-medium">کاربری <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2 p-3 border rounded-2xl @error('building_type') border-red-500 @enderror">
                        <label><input type="checkbox" name="building_type[]" value="مسکونی" {{ is_array(old('building_type')) && in_array('مسکونی', old('building_type')) ? 'checked' : '' }}> مسکونی</label>
                        <label><input type="checkbox" name="building_type[]" value="اداری" {{ is_array(old('building_type')) && in_array('اداری', old('building_type')) ? 'checked' : '' }}> اداری</label>
                        <label><input type="checkbox" name="building_type[]" value="تجاری" {{ is_array(old('building_type')) && in_array('تجاری', old('building_type')) ? 'checked' : '' }}> تجاری</label>
                        <label><input type="checkbox" name="building_type[]" value="هتل" {{ is_array(old('building_type')) && in_array('هتل', old('building_type')) ? 'checked' : '' }}> هتل</label>
                        <label><input type="checkbox" name="building_type[]" value="بیمارستان" {{ is_array(old('building_type')) && in_array('بیمارستان', old('building_type')) ? 'checked' : '' }}> بیمارستان</label>
                        <label><input type="checkbox" name="building_type[]" value="مختلط" {{ is_array(old('building_type')) && in_array('مختلط', old('building_type')) ? 'checked' : '' }}> مختلط</label>
                    </div>
                    @error('building_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 font-medium">تعداد طبقات</label>
                    <input type="number" name="floors" value="{{ old('floors') }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">تعداد بلوک</label>
                    <input type="number" name="blocks" value="{{ old('blocks') }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- بخش ۲: افراد کلیدی -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">افراد کلیدی</h2>
            
            <div class="space-y-4">
                <!-- مدیر پروژه -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_project_manager" name="has_project_manager" value="1" {{ old('has_project_manager') ? 'checked' : '' }} onchange="togglePerson('project_manager')">
                        <span class="font-medium">مدیر پروژه</span>
                    </label>
                    <div id="project_manager_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_project_manager') ? '' : 'hidden' }}">
                        <input type="text" name="project_manager" placeholder="نام" value="{{ old('project_manager') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="project_manager_mobile" placeholder="موبایل" value="{{ old('project_manager_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>

                <!-- مدیر کارگاه -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_site_manager" name="has_site_manager" value="1" {{ old('has_site_manager') ? 'checked' : '' }} onchange="togglePerson('site_manager')">
                        <span class="font-medium">مدیر کارگاه</span>
                    </label>
                    <div id="site_manager_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_site_manager') ? '' : 'hidden' }}">
                        <input type="text" name="site_manager" placeholder="نام" value="{{ old('site_manager') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="site_manager_mobile" placeholder="موبایل" value="{{ old('site_manager_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>

                <!-- سرپرست تأسیسات -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_facilities_supervisor" name="has_facilities_supervisor" value="1" {{ old('has_facilities_supervisor') ? 'checked' : '' }} onchange="togglePerson('facilities_supervisor')">
                        <span class="font-medium">سرپرست تأسیسات</span>
                    </label>
                    <div id="facilities_supervisor_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_facilities_supervisor') ? '' : 'hidden' }}">
                        <input type="text" name="facilities_supervisor" placeholder="نام" value="{{ old('facilities_supervisor') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="facilities_supervisor_mobile" placeholder="موبایل" value="{{ old('facilities_supervisor_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>

                <!-- مسئول خرید -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_purchasing_manager" name="has_purchasing_manager" value="1" {{ old('has_purchasing_manager') ? 'checked' : '' }} onchange="togglePerson('purchasing_manager')">
                        <span class="font-medium">مسئول خرید</span>
                    </label>
                    <div id="purchasing_manager_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_purchasing_manager') ? '' : 'hidden' }}">
                        <input type="text" name="purchasing_manager" placeholder="نام" value="{{ old('purchasing_manager') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="purchasing_manager_mobile" placeholder="موبایل" value="{{ old('purchasing_manager_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>

                <!-- مشاور مکانیک -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_mechanical_consultant" name="has_mechanical_consultant" value="1" {{ old('has_mechanical_consultant') ? 'checked' : '' }} onchange="togglePerson('mechanical_consultant')">
                        <span class="font-medium">مشاور مکانیک</span>
                    </label>
                    <div id="mechanical_consultant_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_mechanical_consultant') ? '' : 'hidden' }}">
                        <input type="text" name="mechanical_consultant" placeholder="نام" value="{{ old('mechanical_consultant') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="mechanical_consultant_mobile" placeholder="موبایل" value="{{ old('mechanical_consultant_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>

                <!-- پیمانکار تأسیسات -->
                <div class="border rounded-2xl p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" id="has_hvac_contractor" name="has_hvac_contractor" value="1" {{ old('has_hvac_contractor') ? 'checked' : '' }} onchange="togglePerson('hvac_contractor')">
                        <span class="font-medium">پیمانکار تأسیسات</span>
                    </label>
                    <div id="hvac_contractor_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old('has_hvac_contractor') ? '' : 'hidden' }}">
                        <input type="text" name="hvac_contractor" placeholder="نام" value="{{ old('hvac_contractor') }}" class="border rounded-xl px-4 py-2">
                        <input type="text" name="hvac_contractor_mobile" placeholder="موبایل" value="{{ old('hvac_contractor_mobile') }}" class="border rounded-xl px-4 py-2">
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش ۳: اطلاعات فنی -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">اطلاعات فنی</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- چیلر -->
                <div class="border rounded-2xl p-4">
                    <label class="block mb-2 font-medium">چیلر دارد؟ <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 @error('has_chiller') border-red-500 @enderror">
                        <label><input type="radio" name="has_chiller" value="yes" {{ old('has_chiller') == 'yes' ? 'checked' : '' }} onchange="toggleChiller()"> بله</label>
                        <label><input type="radio" name="has_chiller" value="no" {{ old('has_chiller') == 'no' ? 'checked' : '' }} onchange="toggleChiller()"> خیر</label>
                    </div>
                    @error('has_chiller')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div id="chiller_fields" class="mt-4 space-y-3 {{ old('has_chiller') == 'yes' ? '' : 'hidden' }}">
                        <input type="text" name="chiller_brand" placeholder="برند چیلر" value="{{ old('chiller_brand') }}" class="w-full border rounded-xl px-4 py-2 @error('chiller_brand') border-red-500 @enderror">
                        @error('chiller_brand')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <input type="file" name="chiller_photo" accept="image/*" class="w-full border rounded-xl px-4 py-2">
                        <p class="text-xs text-gray-500">عکس پلاک چیلر</p>
                    </div>
                </div>

                <!-- برج خنک‌کن -->
                <div class="border rounded-2xl p-4">
                    <label class="block mb-2 font-medium">برج خنک‌کن دارد؟ <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 @error('has_cooling_tower') border-red-500 @enderror">
                        <label><input type="radio" name="has_cooling_tower" value="yes" {{ old('has_cooling_tower') == 'yes' ? 'checked' : '' }} onchange="toggleCoolingTower()"> بله</label>
                        <label><input type="radio" name="has_cooling_tower" value="no" {{ old('has_cooling_tower') == 'no' ? 'checked' : '' }} onchange="toggleCoolingTower()"> خیر</label>
                    </div>
                    @error('has_cooling_tower')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div id="cooling_tower_fields" class="mt-4 space-y-3 {{ old('has_cooling_tower') == 'yes' ? '' : 'hidden' }}">
                        <input type="text" name="current_cooling_brand" placeholder="برند فعلی" value="{{ old('current_cooling_brand') }}" class="w-full border rounded-xl px-4 py-2 @error('current_cooling_brand') border-red-500 @enderror">
                        @error('current_cooling_brand')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <input type="number" step="0.01" name="capacity_tr" placeholder="ظرفیت تقریبی (TR)" value="{{ old('capacity_tr') }}" class="w-full border rounded-xl px-4 py-2 @error('capacity_tr') border-red-500 @enderror">
                        @error('capacity_tr')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <input type="file" name="cooling_tower_photo" accept="image/*" class="w-full border rounded-xl px-4 py-2">
                        <p class="text-xs text-gray-500">عکس پلاک برج خنک‌کن</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش ۴: وضعیت خرید -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">وضعیت خرید</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">وضعیت خرید <span class="text-red-500">*</span></label>
                    </div>
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 font-medium">زمان احتمالی خرید <span class="text-red-500">*</span></label>
                    <input type="text" id="expected_purchase_date_display" value="{{ old('expected_purchase_date_shamsi') }}" onclick="showDateModal2()" readonly class="w-full border rounded-2xl px-4 py-3 cursor-pointer bg-gray-50 @error('expected_purchase_date') border-red-500 @enderror">
                    <input type="hidden" name="expected_purchase_date_shamsi" id="expected_purchase_date_shamsi" value="{{ old('expected_purchase_date_shamsi') }}">
                    <input type="hidden" name="expected_purchase_date" id="expected_purchase_date_gregorian" value="{{ old('expected_purchase_date') }}">
                    @error('expected_purchase_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- بخش ۵: رقبا -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">رقبا</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 font-medium">برندهای رقبا</label>
                    <input type="text" name="competitors" value="{{ old('competitors') }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">فروشنده رقیب</label>
                    <input type="text" name="competitor_seller" value="{{ old('competitor_seller') }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">قیمت تقریبی</label>
                    <input type="number" step="0.01" name="approx_price" value="{{ old('approx_price') }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- بخش ۶: امتیازدهی -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">امتیازدهی پروژه</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">بالای 15 طبقه <span class="text-gray-500 text-sm">(حداکثر 10)</span></label>
                    <select name="score_floor" id="score_floor" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_floor') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_floor') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_floor') == 10 ? 'selected' : '' }}>۱۰ - حداکثر</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">تجاری/هتل <span class="text-gray-500 text-sm">(حداکثر 15)</span></label>
                    <select name="score_building_type" id="score_building_type" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_building_type') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_building_type') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_building_type') == 10 ? 'selected' : '' }}>۱۰</option>
                        <option value="15" {{ old('score_building_type') == 15 ? 'selected' : '' }}>۱۵ - حداکثر</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">فاز تأسیسات <span class="text-gray-500 text-sm">(حداکثر 20)</span></label>
                    <select name="score_facilities_phase" id="score_facilities_phase" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_facilities_phase') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_facilities_phase') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_facilities_phase') == 10 ? 'selected' : '' }}>۱۰</option>
                        <option value="15" {{ old('score_facilities_phase') == 15 ? 'selected' : '' }}>۱۵</option>
                        <option value="20" {{ old('score_facilities_phase') == 20 ? 'selected' : '' }}>۲۰ - حداکثر</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">دسترسی به خرید <span class="text-gray-500 text-sm">(حداکثر 20)</span></label>
                    <select name="score_purchase_access" id="score_purchase_access" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_purchase_access') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_purchase_access') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_purchase_access') == 10 ? 'selected' : '' }}>۱۰</option>
                        <option value="15" {{ old('score_purchase_access') == 15 ? 'selected' : '' }}>۱۵</option>
                        <option value="20" {{ old('score_purchase_access') == 20 ? 'selected' : '' }}>۲۰ - حداکثر</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">هنوز خرید نکرده <span class="text-gray-500 text-sm">(حداکثر 30)</span></label>
                    <select name="score_not_purchased" id="score_not_purchased" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_not_purchased') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_not_purchased') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_not_purchased') == 10 ? 'selected' : '' }}>۱۰</option>
                        <option value="15" {{ old('score_not_purchased') == 15 ? 'selected' : '' }}>۱۵</option>
                        <option value="20" {{ old('score_not_purchased') == 20 ? 'selected' : '' }}>۲۰</option>
                        <option value="25" {{ old('score_not_purchased') == 25 ? 'selected' : '' }}>۲۵</option>
                        <option value="30" {{ old('score_not_purchased') == 30 ? 'selected' : '' }}>۳۰ - حداکثر</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">ظرفیت بالا <span class="text-gray-500 text-sm">(حداکثر 20)</span></label>
                    <select name="score_capacity" id="score_capacity" class="w-full border rounded-2xl px-4 py-3 score-select">
                        <option value="0" {{ old('score_capacity') == 0 ? 'selected' : '' }}>۰ - بدون امتیاز</option>
                        <option value="5" {{ old('score_capacity') == 5 ? 'selected' : '' }}>۵</option>
                        <option value="10" {{ old('score_capacity') == 10 ? 'selected' : '' }}>۱۰</option>
                        <option value="15" {{ old('score_capacity') == 15 ? 'selected' : '' }}>۱۵</option>
                        <option value="20" {{ old('score_capacity') == 20 ? 'selected' : '' }}>۲۰ - حداکثر</option>
                    </select>
                </div>
            </div>
            
            <!-- نمایش امتیاز کل -->
            <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl border-2 border-green-300">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg text-gray-700">🌟 امتیاز کل:</span>
                    <span id="totalScoreDisplay" class="text-3xl font-bold text-green-600">0</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-500">حداکثر امتیاز قابل کسب: <strong class="text-gray-700">115</strong></span>
                    <span class="text-sm text-gray-500">امتیازها به صورت خودکار جمع می‌شوند</span>
                </div>
            </div>

            <div class="mt-6">
                <label class="block mb-2 font-medium">سطح پروژه <span class="text-red-500">*</span></label>
                <div class="flex gap-6 p-3 border rounded-2xl @error('level') border-red-500 @enderror">
                    <label class="flex items-center gap-1">
                        <input type="radio" name="level" value="A" {{ old('level') == 'A' ? 'checked' : '' }}> 
                        <span class="font-bold text-red-600">🔥 A (داغ)</span>
                        <span class="text-sm text-gray-500">- امتیاز 70+</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="level" value="B" {{ old('level') == 'B' ? 'checked' : '' }}> 
                        <span class="font-bold text-yellow-600">⏳ B (پیگیری)</span>
                        <span class="text-sm text-gray-500">- امتیاز 40-69</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="level" value="C" {{ old('level') == 'C' ? 'checked' : '' }}> 
                        <span class="font-bold text-gray-600">📁 C (آرشیو)</span>
                        <span class="text-sm text-gray-500">- امتیاز کمتر از 40</span>
                    </label>
                </div>
                @error('level')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- بخش ۷: یادداشت‌ها -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-green-500 pb-3 mb-6">یادداشت‌ها</h2>
            <textarea name="notes" rows="4" class="w-full border rounded-2xl px-4 py-3">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-bold text-lg transition">
            ثبت نهایی فرم بازدید
        </button>
    </form>
</div>

<!-- Modal تقویم ۱: تاریخ بازدید -->
<div id="dateModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">انتخاب تاریخ بازدید</h3>
        <div class="grid grid-cols-3 gap-4">
            <select id="year" onchange="updateDays()" class="border rounded-2xl p-4"></select>
            <select id="month" onchange="updateDays()" class="border rounded-2xl p-4"></select>
            <select id="day" class="border rounded-2xl p-4"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideDateModal()" class="flex-1 py-4 border rounded-2xl">انصراف</button>
            <button onclick="selectDate()" class="flex-1 bg-green-600 text-white py-4 rounded-2xl">تأیید</button>
        </div>
    </div>
</div>

<!-- Modal تقویم ۲: زمان احتمالی خرید -->
<div id="dateModal2" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">انتخاب زمان احتمالی خرید</h3>
        <div class="grid grid-cols-3 gap-4">
            <select id="year2" onchange="updateDays2()" class="border rounded-2xl p-4"></select>
            <select id="month2" onchange="updateDays2()" class="border rounded-2xl p-4"></select>
            <select id="day2" class="border rounded-2xl p-4"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideDateModal2()" class="flex-1 py-4 border rounded-2xl">انصراف</button>
            <button onclick="selectDate2()" class="flex-1 bg-green-600 text-white py-4 rounded-2xl">تأیید</button>
        </div>
    </div>
</div>

<script>
// ===== توابع مربوط به افراد کلیدی =====
function togglePerson(type) {
    const checkbox = document.getElementById('has_' + type);
    const fields = document.getElementById(type + '_fields');
    if (checkbox.checked) {
        fields.classList.remove('hidden');
        fields.querySelectorAll('input').forEach(input => input.required = true);
    } else {
        fields.classList.add('hidden');
        fields.querySelectorAll('input').forEach(input => {
            input.required = false;
            input.value = '';
        });
    }
}

// ===== توابع مربوط به چیلر =====
function toggleChiller() {
    const hasChiller = document.querySelector('input[name="has_chiller"]:checked');
    const fields = document.getElementById('chiller_fields');
    if (hasChiller && hasChiller.value === 'yes') {
        fields.classList.remove('hidden');
        fields.querySelectorAll('input').forEach(input => input.required = true);
    } else {
        fields.classList.add('hidden');
        fields.querySelectorAll('input').forEach(input => {
            input.required = false;
            if (input.type !== 'file') input.value = '';
        });
    }
}

// ===== توابع مربوط به برج خنک‌کن =====
function toggleCoolingTower() {
    const hasCoolingTower = document.querySelector('input[name="has_cooling_tower"]:checked');
    const fields = document.getElementById('cooling_tower_fields');
    if (hasCoolingTower && hasCoolingTower.value === 'yes') {
        fields.classList.remove('hidden');
        fields.querySelectorAll('input').forEach(input => input.required = true);
    } else {
        fields.classList.add('hidden');
        fields.querySelectorAll('input').forEach(input => {
            input.required = false;
            if (input.type !== 'file') input.value = '';
        });
    }
}

// ===== محاسبه خودکار امتیاز کل =====
document.addEventListener('DOMContentLoaded', function() {
    const scoreSelects = document.querySelectorAll('.score-select');
    const totalDisplay = document.getElementById('totalScoreDisplay');
    
    function calculateTotal() {
        let total = 0;
        scoreSelects.forEach(function(select) {
            const val = parseInt(select.value) || 0;
            total += val;
        });
        totalDisplay.textContent = total;
        
        if (total >= 70) {
            totalDisplay.className = 'text-3xl font-bold text-red-600';
        } else if (total >= 40) {
            totalDisplay.className = 'text-3xl font-bold text-yellow-600';
        } else {
            totalDisplay.className = 'text-3xl font-bold text-gray-600';
        }
    }
    
    scoreSelects.forEach(function(select) {
        select.addEventListener('change', calculateTotal);
    });
    
    calculateTotal();
});

// ===== توابع مربوط به تاریخ بازدید =====
function showDateModal() {
    document.getElementById('dateModal').classList.remove('hidden');
    populateCalendar();
}

function hideDateModal() {
    document.getElementById('dateModal').classList.add('hidden');
}

function populateCalendar() {
    const currentJYear = new Date().getFullYear() - 621;
    const yearSelect = document.getElementById('year');
    yearSelect.innerHTML = '';
    for (let y = currentJYear - 5; y <= currentJYear + 5; y++) {
        let opt = new Option(y, y);
        if (y === currentJYear) opt.selected = true;
        yearSelect.add(opt);
    }
    const months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const monthSelect = document.getElementById('month');
    monthSelect.innerHTML = '';
    months.forEach((name, i) => monthSelect.add(new Option(name, i+1)));
    updateDays();
}

function updateDays() {
    const daySelect = document.getElementById('day');
    daySelect.innerHTML = '';
    for (let d = 1; d <= 31; d++) daySelect.add(new Option(d, d));
}

function selectDate() {
    const y = parseInt(document.getElementById('year').value);
    const m = parseInt(document.getElementById('month').value);
    const d = document.getElementById('day').value.padStart(2, '0');

    const shamsi = `${y}/${m.toString().padStart(2, '0')}/${d}`;

    document.getElementById('visit_date_display').value = shamsi;
    document.getElementById('visit_date_shamsi').value = shamsi;

    let gy = y + 621;
    let gm = m <= 6 ? m + 3 : m - 9;
    if (m > 6) gy++;

    const gregorian = `${gy}-${gm.toString().padStart(2, '0')}-${d}`;
    document.getElementById('visit_date_gregorian').value = gregorian;

    hideDateModal();
}

// ===== توابع مربوط به زمان احتمالی خرید =====
function showDateModal2() {
    document.getElementById('dateModal2').classList.remove('hidden');
    populateCalendar2();
}

function hideDateModal2() {
    document.getElementById('dateModal2').classList.add('hidden');
}

function populateCalendar2() {
    const currentJYear = new Date().getFullYear() - 621;
    const yearSelect = document.getElementById('year2');
    yearSelect.innerHTML = '';
    for (let y = currentJYear - 5; y <= currentJYear + 5; y++) {
        let opt = new Option(y, y);
        if (y === currentJYear) opt.selected = true;
        yearSelect.add(opt);
    }
    const months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const monthSelect = document.getElementById('month2');
    monthSelect.innerHTML = '';
    months.forEach((name, i) => monthSelect.add(new Option(name, i+1)));
    updateDays2();
}

function updateDays2() {
    const daySelect = document.getElementById('day2');
    daySelect.innerHTML = '';
    for (let d = 1; d <= 31; d++) daySelect.add(new Option(d, d));
}

function selectDate2() {
    const y = parseInt(document.getElementById('year2').value);
    const m = parseInt(document.getElementById('month2').value);
    const d = document.getElementById('day2').value.padStart(2, '0');

    const shamsi = `${y}/${m.toString().padStart(2, '0')}/${d}`;

    document.getElementById('expected_purchase_date_display').value = shamsi;
    document.getElementById('expected_purchase_date_shamsi').value = shamsi;

    let gy = y + 621;
    let gm = m <= 6 ? m + 3 : m - 9;
    if (m > 6) gy++;

    const gregorian = `${gy}-${gm.toString().padStart(2, '0')}-${d}`;
    document.getElementById('expected_purchase_date_gregorian').value = gregorian;

    hideDateModal2();
}

// اجرای اولیه توابع
document.addEventListener('DOMContentLoaded', function() {
    toggleChiller();
    toggleCoolingTower();
});

// اعتبارسنجی سمت کلاینت قبل از ارسال فرم
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let hasError = false;
        let errorMessages = [];

        // بررسی کاربری
        const checkboxes = document.querySelectorAll('input[name="building_type[]"]:checked');
        if (checkboxes.length === 0) {
            hasError = true;
            errorMessages.push('لطفاً حداقل یک مورد از کاربری را انتخاب کنید.');
        }

        // بررسی وضعیت خرید
        if (!purchaseStage) {
            hasError = true;
            errorMessages.push('لطفاً وضعیت خرید را انتخاب کنید.');
        }

        // بررسی سطح پروژه
        const level = document.querySelector('input[name="level"]:checked');
        if (!level) {
            hasError = true;
            errorMessages.push('لطفاً سطح پروژه را انتخاب کنید.');
        }

        // بررسی تاریخ خرید
        const purchaseDate = document.getElementById('expected_purchase_date_gregorian');
        if (!purchaseDate.value) {
            hasError = true;
            errorMessages.push('لطفاً زمان احتمالی خرید را انتخاب کنید.');
        }

        // بررسی چیلر
        const hasChiller = document.querySelector('input[name="has_chiller"]:checked');
        if (!hasChiller) {
            hasError = true;
            errorMessages.push('لطفاً مشخص کنید که چیلر دارد یا خیر.');
        }

        // بررسی برج خنک‌کن
        const hasCoolingTower = document.querySelector('input[name="has_cooling_tower"]:checked');
        if (!hasCoolingTower) {
            hasError = true;
            errorMessages.push('لطفاً مشخص کنید که برج خنک‌کن دارد یا خیر.');
        }

        if (hasError) {
            e.preventDefault();
            alert('❌ خطا:\n\n' + errorMessages.join('\n'));
        }
    });
});
</script>
@endsection