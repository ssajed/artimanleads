@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-[#04BA07] dark:text-green-400 mb-2">🏗️ فرم بازدید پروژه</h1>
            <p class="text-gray-600 dark:text-gray-400">لطفاً اطلاعات زیر را با دقت تکمیل کنید. فیلدهای ستاره‌دار الزامی هستند.</p>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700 transition-colors" enctype="multipart/form-data">
            @csrf

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-5 py-4 rounded-2xl mb-6 flex items-start gap-3">
                    <span class="text-xl"></span>
                    <div>
                        <p class="font-bold mb-1">لطفاً خطاهای زیر را برطرف کنید:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-2xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- بخش ۱: اطلاعات پروژه -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">اطلاعات پروژه</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">تاریخ بازدید <span class="text-red-500">*</span></label>
                        <input type="text" id="visit_date_display" value="{{ old('visit_date_shamsi') ?? \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}" onclick="showDateModal()" readonly class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 cursor-pointer bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('visit_date') border-red-500 @enderror">
                        <input type="hidden" name="visit_date_shamsi" id="visit_date_shamsi" value="{{ old('visit_date_shamsi') }}">
                        <input type="hidden" name="visit_date" id="visit_date_gregorian" value="{{ old('visit_date') }}">
                        @error('visit_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">نام بازاریاب</label>
                        <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-gray-100 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">نام پروژه <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">آدرس <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" required class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('address') border-red-500 @enderror">
                        @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">منطقه <span class="text-red-500">*</span></label>
                        <input type="text" name="region" value="{{ old('region') }}" required class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('region') border-red-500 @enderror">
                        @error('region') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">کاربری <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2 p-3 border border-gray-300 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 @error('building_type') border-red-500 @enderror">
                            @php $types = ['مسکونی', 'اداری', 'تجاری', 'هتل', 'بیمارستان', 'مختلط']; @endphp
                            @foreach($types as $type)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="checkbox" name="building_type[]" value="{{ $type }}" 
                                        {{ is_array(old('building_type')) && in_array($type, old('building_type')) ? 'checked' : '' }}
                                        class="rounded text-[#04BA07] focus:ring-[#04BA07]">
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                        @error('building_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">تعداد طبقات</label>
                        <input type="number" name="floors" value="{{ old('floors') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">تعداد بلوک</label>
                        <input type="number" name="blocks" value="{{ old('blocks') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                    </div>
                </div>
            </div>

            <!-- بخش ۲: افراد کلیدی -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">افراد کلیدی</h2>
                
                <div class="space-y-4">
                    @php
                        $persons = [
                            ['id' => 'project_manager', 'label' => 'مدیر پروژه'],
                            ['id' => 'site_manager', 'label' => 'مدیر کارگاه'],
                            ['id' => 'facilities_supervisor', 'label' => 'سرپرست تأسیسات'],
                            ['id' => 'purchasing_manager', 'label' => 'مسئول خرید'],
                            ['id' => 'mechanical_consultant', 'label' => 'مشاور مکانیک'],
                            ['id' => 'hvac_contractor', 'label' => 'پیمانکار تأسیسات']
                        ];
                    @endphp
                    
                    @foreach($persons as $person)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-4 bg-white dark:bg-gray-800">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="has_{{ $person['id'] }}" name="has_{{ $person['id'] }}" value="1" 
                                    {{ old("has_{$person['id']}") ? 'checked' : '' }} 
                                    onchange="togglePerson('{{ $person['id'] }}')"
                                    class="rounded text-[#04BA07] focus:ring-[#04BA07] w-5 h-5">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $person['label'] }}</span>
                            </label>
                            <div id="{{ $person['id'] }}_fields" class="grid grid-cols-2 gap-4 mt-3 {{ old("has_{$person['id']}") ? '' : 'hidden' }}">
                                <input type="text" name="{{ $person['id'] }}" placeholder="نام" value="{{ old($person['id']) }}" 
                                    class="border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                                <input type="text" name="{{ $person['id'] }}_mobile" placeholder="موبایل" value="{{ old("{$person['id']}_mobile") }}" 
                                    class="border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- بخش ۳: اطلاعات فنی -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">اطلاعات فنی</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- چیلر -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-4 bg-white dark:bg-gray-800">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">چیلر دارد؟ <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer text-gray-700 dark:text-gray-300">
                                <input type="radio" name="has_chiller" value="yes" {{ old('has_chiller') == 'yes' ? 'checked' : '' }} onchange="toggleChiller()" class="text-[#04BA07] focus:ring-[#04BA07]"> بله
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-gray-700 dark:text-gray-300">
                                <input type="radio" name="has_chiller" value="no" {{ old('has_chiller') == 'no' ? 'checked' : '' }} onchange="toggleChiller()" class="text-[#04BA07] focus:ring-[#04BA07]"> خیر
                            </label>
                        </div>
                        @error('has_chiller') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        
                        <div id="chiller_fields" class="mt-4 space-y-3 {{ old('has_chiller') == 'yes' ? '' : 'hidden' }}">
                            <input type="text" name="chiller_brand" placeholder="برند چیلر" value="{{ old('chiller_brand') }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('chiller_brand') border-red-500 @enderror">
                            @error('chiller_brand') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            
                            <div class="relative">
                                <input type="file" name="chiller_photo" accept="image/*" 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#04BA07] hover:file:bg-green-100 dark:file:bg-gray-600 dark:file:text-green-400">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">عکس پلاک چیلر</p>
                            </div>
                        </div>
                    </div>

                    <!-- برج خنک‌کن -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-4 bg-white dark:bg-gray-800">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">برج خنک‌کن دارد؟ <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer text-gray-700 dark:text-gray-300">
                                <input type="radio" name="has_cooling_tower" value="yes" {{ old('has_cooling_tower') == 'yes' ? 'checked' : '' }} onchange="toggleCoolingTower()" class="text-[#04BA07] focus:ring-[#04BA07]"> بله
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-gray-700 dark:text-gray-300">
                                <input type="radio" name="has_cooling_tower" value="no" {{ old('has_cooling_tower') == 'no' ? 'checked' : '' }} onchange="toggleCoolingTower()" class="text-[#04BA07] focus:ring-[#04BA07]"> خیر
                            </label>
                        </div>
                        @error('has_cooling_tower') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        
                        <div id="cooling_tower_fields" class="mt-4 space-y-3 {{ old('has_cooling_tower') == 'yes' ? '' : 'hidden' }}">
                            <input type="text" name="current_cooling_brand" placeholder="برند فعلی" value="{{ old('current_cooling_brand') }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('current_cooling_brand') border-red-500 @enderror">
                            @error('current_cooling_brand') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            
                            <input type="number" step="0.01" name="capacity_tr" placeholder="ظرفیت تقریبی (TR)" value="{{ old('capacity_tr') }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('capacity_tr') border-red-500 @enderror">
                            @error('capacity_tr') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            
                            <div class="relative">
                                <input type="file" name="cooling_tower_photo" accept="image/*" 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#04BA07] hover:file:bg-green-100 dark:file:bg-gray-600 dark:file:text-green-400">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">عکس پلاک برج خنک‌کن</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بخش ۴: وضعیت خرید -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">وضعیت خرید</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">وضعیت خرید <span class="text-red-500">*</span></label>
                        <select name="purchase_status" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('purchase_status') border-red-500 @enderror">
                            <option value="">انتخاب کنید</option>
                            <option value="not_purchased" {{ old('purchase_status') == 'not_purchased' ? 'selected' : '' }}>هنوز خرید نکرده</option>
                            <option value="in_progress" {{ old('purchase_status') == 'in_progress' ? 'selected' : '' }}>در حال بررسی / مذاکره</option>
                            <option value="purchased" {{ old('purchase_status') == 'purchased' ? 'selected' : '' }}>خرید انجام شده</option>
                        </select>
                        @error('purchase_status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">زمان احتمالی خرید <span class="text-red-500">*</span></label>
                        <input type="text" id="expected_purchase_date_display" value="{{ old('expected_purchase_date_shamsi') }}" onclick="showDateModal2()" readonly 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 cursor-pointer bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07] @error('expected_purchase_date') border-red-500 @enderror">
                        <input type="hidden" name="expected_purchase_date_shamsi" id="expected_purchase_date_shamsi" value="{{ old('expected_purchase_date_shamsi') }}">
                        <input type="hidden" name="expected_purchase_date" id="expected_purchase_date_gregorian" value="{{ old('expected_purchase_date') }}">
                        @error('expected_purchase_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- بخش ۵: رقبا -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">رقبا</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">برندهای رقبا</label>
                        <input type="text" name="competitors" value="{{ old('competitors') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">فروشنده رقیب</label>
                        <input type="text" name="competitor_seller" value="{{ old('competitor_seller') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">قیمت تقریبی</label>
                        <input type="number" step="0.01" name="approx_price" value="{{ old('approx_price') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                    </div>
                </div>
            </div>

            <!-- بخش ۶: امتیازدهی -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">امتیازدهی پروژه</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $scores = [
                            ['name' => 'score_floor', 'label' => 'بالای 15 طبقه', 'max' => 10, 'options' => [0, 5, 10]],
                            ['name' => 'score_building_type', 'label' => 'تجاری/هتل', 'max' => 15, 'options' => [0, 5, 10, 15]],
                            ['name' => 'score_facilities_phase', 'label' => 'فاز تأسیسات', 'max' => 20, 'options' => [0, 5, 10, 15, 20]],
                            ['name' => 'score_purchase_access', 'label' => 'دسترسی به خرید', 'max' => 20, 'options' => [0, 5, 10, 15, 20]],
                            ['name' => 'score_not_purchased', 'label' => 'هنوز خرید نکرده', 'max' => 30, 'options' => [0, 5, 10, 15, 20, 25, 30]],
                            ['name' => 'score_capacity', 'label' => 'ظرفیت بالا', 'max' => 20, 'options' => [0, 5, 10, 15, 20]]
                        ];
                    @endphp
                    
                    @foreach($scores as $score)
                        <div>
                            <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">{{ $score['label'] }} <span class="text-gray-500 dark:text-gray-400 text-sm">(حداکثر {{ $score['max'] }})</span></label>
                            <select name="{{ $score['name'] }}" id="{{ $score['name'] }}" class="score-select w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">
                                @foreach($score['options'] as $opt)
                                    <option value="{{ $opt }}" {{ old($score['name']) == $opt ? 'selected' : '' }}>
                                        {{ $opt == 0 ? '۰ - بدون امتیاز' : "$opt" . ($opt == $score['max'] ? ' - حداکثر' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
                
                <!-- نمایش امتیاز کل -->
                <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-2xl border-2 border-green-300 dark:border-green-800">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg text-gray-700 dark:text-gray-200">🌟 امتیاز کل:</span>
                        <span id="totalScoreDisplay" class="text-3xl font-bold text-green-600 dark:text-green-400">0</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">حداکثر امتیاز قابل کسب: <strong class="text-gray-700 dark:text-gray-200">115</strong></span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">امتیازها به صورت خودکار جمع می‌شوند</span>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">سطح پروژه <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-6 p-3 border border-gray-300 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 @error('level') border-red-500 @enderror">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="level" value="A" {{ old('level') == 'A' ? 'checked' : '' }} class="text-red-600 focus:ring-red-500"> 
                            <span class="font-bold text-red-600 dark:text-red-400">🔥 A (داغ)</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">- امتیاز 70+</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="level" value="B" {{ old('level') == 'B' ? 'checked' : '' }} class="text-yellow-600 focus:ring-yellow-500"> 
                            <span class="font-bold text-yellow-600 dark:text-yellow-400">⏳ B (پیگیری)</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">- امتیاز 40-69</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="level" value="C" {{ old('level') == 'C' ? 'checked' : '' }} class="text-gray-600 focus:ring-gray-500"> 
                            <span class="font-bold text-gray-600 dark:text-gray-400"> C (آرشیو)</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">- امتیاز کمتر از 40</span>
                        </label>
                    </div>
                    @error('level') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- بخش ۷: یادداشت‌ها -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white border-b-4 border-[#04BA07] pb-3 mb-6">یادداشت‌ها</h2>
                <textarea name="notes" rows="4" placeholder="یادداشت‌های اضافی..." 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#04BA07] hover:bg-green-700 text-white py-4 rounded-2xl font-bold text-lg transition shadow-lg shadow-green-500/20">
                ثبت نهایی فرم بازدید
            </button>
        </form>
    </div>
</div>

<!-- Modal تقویم ۱: تاریخ بازدید -->
<div id="dateModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md border border-gray-200 dark:border-gray-700 shadow-2xl">
        <h3 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white">انتخاب تاریخ بازدید</h3>
        <div class="grid grid-cols-3 gap-4">
            <select id="year" onchange="updateDays()" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
            <select id="month" onchange="updateDays()" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
            <select id="day" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideDateModal()" class="flex-1 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">انصراف</button>
            <button onclick="selectDate()" class="flex-1 bg-[#04BA07] text-white py-4 rounded-2xl hover:bg-green-700 transition font-bold">تأیید</button>
        </div>
    </div>
</div>

<!-- Modal تقویم ۲: زمان احتمالی خرید -->
<div id="dateModal2" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md border border-gray-200 dark:border-gray-700 shadow-2xl">
        <h3 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white">انتخاب زمان احتمالی خرید</h3>
        <div class="grid grid-cols-3 gap-4">
            <select id="year2" onchange="updateDays2()" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
            <select id="month2" onchange="updateDays2()" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
            <select id="day2" class="border border-gray-300 dark:border-gray-600 rounded-2xl p-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-[#04BA07] focus:ring-[#04BA07]"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideDateModal2()" class="flex-1 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">انصراف</button>
            <button onclick="selectDate2()" class="flex-1 bg-[#04BA07] text-white py-4 rounded-2xl hover:bg-green-700 transition font-bold">تأیید</button>
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
            totalDisplay.className = 'text-3xl font-bold text-red-600 dark:text-red-400';
        } else if (total >= 40) {
            totalDisplay.className = 'text-3xl font-bold text-yellow-600 dark:text-yellow-400';
        } else {
            totalDisplay.className = 'text-3xl font-bold text-gray-600 dark:text-gray-400';
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
        const purchaseStatus = document.querySelector('select[name="purchase_status"]');
        if (!purchaseStatus || !purchaseStatus.value) {
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
        if (!purchaseDate || !purchaseDate.value) {
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