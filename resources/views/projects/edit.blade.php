@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">ویرایش پروژه: {{ $project->title }}</h1>
        <a href="{{ route('projects.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl font-medium transition">
            🔙 بازگشت
        </a>
    </div>

    <form action="{{ route('projects.update', $project) }}" method="POST" class="bg-white rounded-3xl shadow-xl p-8">
        @csrf
        @method('PUT')

        <!-- بخش ۱: اطلاعات پروژه -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">اطلاعات پروژه</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">تاریخ بازدید <span class="text-red-500">*</span></label>
                    <input type="text" id="visit_date_display" value="{{ old('visit_date_shamsi', $project->visit_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($project->visit_date))->format('Y/m/d') : '') }}" onclick="showDateModal()" readonly class="w-full border rounded-2xl px-4 py-3 cursor-pointer bg-gray-50">
                    <input type="hidden" name="visit_date_shamsi" id="visit_date_shamsi" value="{{ old('visit_date_shamsi', $project->visit_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($project->visit_date))->format('Y/m/d') : '') }}">
                    <input type="hidden" name="visit_date" id="visit_date_gregorian" value="{{ old('visit_date', $project->visit_date) }}">
                </div>
                <div>
					<label class="block mb-2 font-medium">نام بازاریاب</label>
					<input type="text" name="marketer_name" value="{{ auth()->user()->name }}" readonly class="w-full border rounded-2xl px-4 py-3 bg-gray-100 cursor-not-allowed">
				</div>
                <div>
                    <label class="block mb-2 font-medium">نام پروژه <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" required class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">آدرس <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $project->address) }}" required class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">منطقه <span class="text-red-500">*</span></label>
                    <input type="text" name="region" value="{{ old('region', $project->region) }}" required class="w-full border rounded-2xl px-4 py-3">
                </div>
               <div>
    <label class="block mb-2 font-medium">کاربری <span class="text-red-500">*</span></label>
    <div class="grid grid-cols-3 gap-2 p-3 border rounded-2xl">
        @php
            $buildingTypes = old('building_type', is_array($project->building_type) ? $project->building_type : json_decode($project->building_type ?? '[]', true));
        @endphp
        <label><input type="checkbox" name="building_type[]" value="مسکونی" {{ is_array($buildingTypes) && in_array('مسکونی', $buildingTypes) ? 'checked' : '' }}> مسکونی</label>
        <label><input type="checkbox" name="building_type[]" value="اداری" {{ is_array($buildingTypes) && in_array('اداری', $buildingTypes) ? 'checked' : '' }}> اداری</label>
        <label><input type="checkbox" name="building_type[]" value="تجاری" {{ is_array($buildingTypes) && in_array('تجاری', $buildingTypes) ? 'checked' : '' }}> تجاری</label>
        <label><input type="checkbox" name="building_type[]" value="هتل" {{ is_array($buildingTypes) && in_array('هتل', $buildingTypes) ? 'checked' : '' }}> هتل</label>
        <label><input type="checkbox" name="building_type[]" value="بیمارستان" {{ is_array($buildingTypes) && in_array('بیمارستان', $buildingTypes) ? 'checked' : '' }}> بیمارستان</label>
        <label><input type="checkbox" name="building_type[]" value="مختلط" {{ is_array($buildingTypes) && in_array('مختلط', $buildingTypes) ? 'checked' : '' }}> مختلط</label>
    </div>
    @error('building_type')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
                <div>
                    <label class="block mb-2 font-medium">تعداد طبقات</label>
                    <input type="number" name="floors" value="{{ old('floors', $project->floors) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">تعداد بلوک</label>
                    <input type="number" name="blocks" value="{{ old('blocks', $project->blocks) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- بخش ۲: افراد کلیدی -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">افراد کلیدی</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-right">سمت</th>
                            <th class="border p-3 text-right">نام</th>
                            <th class="border p-3 text-right">موبایل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-3 font-medium">مدیر پروژه</td>
                            <td class="border p-3"><input type="text" name="project_manager" value="{{ old('project_manager', $project->project_manager) }}" class="w-full border rounded-xl px-3 py-2"></td>
                            <td class="border p-3"><input type="text" name="project_manager_mobile" value="{{ old('project_manager_mobile', $project->project_manager_mobile) }}" class="w-full border rounded-xl px-3 py-2"></td>
                        </tr>
                        <tr>
                            <td class="border p-3 font-medium">مدیر کارگاه</td>
                            <td class="border p-3"><input type="text" name="site_manager" value="{{ old('site_manager', $project->site_manager) }}" class="w-full border rounded-xl px-3 py-2"></td>
                            <td class="border p-3"><input type="text" name="site_manager_mobile" value="{{ old('site_manager_mobile', $project->site_manager_mobile) }}" class="w-full border rounded-xl px-3 py-2"></td>
                        </tr>
                        <tr>
                            <td class="border p-3 font-medium">سرپرست تأسیسات</td>
                            <td class="border p-3"><input type="text" name="facilities_supervisor" value="{{ old('facilities_supervisor', $project->facilities_supervisor) }}" class="w-full border rounded-xl px-3 py-2"></td>
                            <td class="border p-3"><input type="text" name="facilities_supervisor_mobile" value="{{ old('facilities_supervisor_mobile', $project->facilities_supervisor_mobile) }}" class="w-full border rounded-xl px-3 py-2"></td>
                        </tr>
                        <tr>
                            <td class="border p-3 font-medium">مسئول خرید</td>
                            <td class="border p-3"><input type="text" name="purchasing_manager" value="{{ old('purchasing_manager', $project->purchasing_manager) }}" class="w-full border rounded-xl px-3 py-2"></td>
                            <td class="border p-3"><input type="text" name="purchasing_manager_mobile" value="{{ old('purchasing_manager_mobile', $project->purchasing_manager_mobile) }}" class="w-full border rounded-xl px-3 py-2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- بخش ۳: اطلاعات فنی -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">اطلاعات فنی</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">مشاور مکانیک</label>
                    <input type="text" name="mechanical_consultant" value="{{ old('mechanical_consultant', $project->mechanical_consultant) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">پیمانکار تأسیسات</label>
                    <input type="text" name="hvac_contractor" value="{{ old('hvac_contractor', $project->hvac_contractor) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">چیلر انتخاب شده</label>
                    <div class="flex gap-4 p-3 border rounded-2xl">
                        <label><input type="radio" name="chiller_selected" value="yes" {{ old('chiller_selected', $project->chiller_selected) == 'yes' ? 'checked' : '' }}> بله</label>
                        <label><input type="radio" name="chiller_selected" value="no" {{ old('chiller_selected', $project->chiller_selected) == 'no' ? 'checked' : '' }}> خیر</label>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-medium">برند چیلر</label>
                    <input type="text" name="chiller_brand" value="{{ old('chiller_brand', $project->chiller_brand) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">برج خنک‌کن انتخاب شده</label>
                    <div class="flex gap-4 p-3 border rounded-2xl">
                        <label><input type="radio" name="cooling_tower_selected" value="yes" {{ old('cooling_tower_selected', $project->cooling_tower_selected) == 'yes' ? 'checked' : '' }}> بله</label>
                        <label><input type="radio" name="cooling_tower_selected" value="no" {{ old('cooling_tower_selected', $project->cooling_tower_selected) == 'no' ? 'checked' : '' }}> خیر</label>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-medium">برند فعلی</label>
                    <input type="text" name="current_cooling_brand" value="{{ old('current_cooling_brand', $project->current_cooling_brand) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">ظرفیت تقریبی (TR)</label>
                    <input type="number" step="0.01" name="capacity_tr" value="{{ old('capacity_tr', $project->capacity_tr) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- بخش ۴: وضعیت خرید -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">وضعیت خرید</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">وضعیت خرید</label>
                    <div class="flex flex-wrap gap-4 p-3 border rounded-2xl">
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-medium">زمان احتمالی خرید</label>
                    <input type="date" name="expected_purchase_date" value="{{ old('expected_purchase_date', $project->expected_purchase_date) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- بخش ۵: رقبا -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">رقبا</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 font-medium">برندهای رقبا</label>
                    <input type="text" name="competitors" value="{{ old('competitors', $project->competitors) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">فروشنده رقیب</label>
                    <input type="text" name="competitor_seller" value="{{ old('competitor_seller', $project->competitor_seller) }}" class="w-full border rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 font-medium">قیمت تقریبی</label>
                    <input type="number" step="0.01" name="approx_price" value="{{ old('approx_price', $project->approx_price) }}" class="w-full border rounded-2xl px-4 py-3">
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
        <label class="block mb-2 font-medium">سطح پروژه</label>
        <div class="flex gap-6 p-3 border rounded-2xl">
            <label class="flex items-center gap-1">
                <input type="radio" name="level" value="A" {{ old('level') == 'A' ? 'checked' : '' }}> 
                <span class="font-bold text-red-600">🔥 A (داغ)</span>
            </label>
            <label class="flex items-center gap-1">
                <input type="radio" name="level" value="B" {{ old('level') == 'B' ? 'checked' : '' }}> 
                <span class="font-bold text-yellow-600">⏳ B (پیگیری)</span>
            </label>
            <label class="flex items-center gap-1">
                <input type="radio" name="level" value="C" {{ old('level') == 'C' ? 'checked' : '' }}> 
                <span class="font-bold text-gray-600">📁 C (آرشیو)</span>
            </label>
        </div>
    </div>
</div>



        <!-- بخش ۷: یادداشت‌ها -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 border-b-4 border-yellow-500 pb-3 mb-6">یادداشت‌ها</h2>
            <textarea name="notes" rows="4" class="w-full border rounded-2xl px-4 py-3">{{ old('notes', $project->notes) }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-4 rounded-2xl font-bold text-lg transition">
                💾 ذخیره تغییرات
            </button>
            <a href="{{ route('projects.show', $project) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-4 rounded-2xl font-bold text-lg transition text-center">
                ❌ انصراف
            </a>
        </div>
    </form>
</div>

<!-- Modal تقویم -->
<div id="dateModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">انتخاب تاریخ شمسی</h3>
        <div class="grid grid-cols-3 gap-4">
            <select id="year" onchange="updateDays()" class="border rounded-2xl p-4"></select>
            <select id="month" onchange="updateDays()" class="border rounded-2xl p-4"></select>
            <select id="day" class="border rounded-2xl p-4"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideDateModal()" class="flex-1 py-4 border rounded-2xl">انصراف</button>
            <button onclick="selectDate()" class="flex-1 bg-yellow-500 text-white py-4 rounded-2xl">تأیید</button>
        </div>
    </div>
</div>

<script>
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
</script>
@endsection