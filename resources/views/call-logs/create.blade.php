@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">📞 ثبت تماس جدید</h1>
    
    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <p><strong>پروژه:</strong> {{ $project->title }}</p>
            <p><strong>آدرس:</strong> {{ $project->address }}</p>
        </div>

        <form action="{{ route('call-logs.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block mb-2 font-medium">تاریخ و زمان تماس <span class="text-red-500">*</span></label>
                    <input type="text" id="call_date_display" value="{{ old('call_date_shamsi') }}" 
                           onclick="showCallDateModal()" readonly 
                           class="w-full border rounded-lg px-4 py-2 @error('call_date_shamsi') border-red-500 @enderror cursor-pointer bg-gray-50">
                    <input type="hidden" name="call_date_shamsi" id="call_date_shamsi" value="{{ old('call_date_shamsi') }}">
                    <input type="hidden" name="call_date" id="call_date_gregorian" value="{{ old('call_date') }}">
                    @error('call_date_shamsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-medium">موضوع تماس <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('subject') border-red-500 @enderror" required>
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">شخص تماس گیرنده <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <select name="contact_person" id="contact_person" 
                            class="w-full border rounded-lg px-4 py-2 @error('contact_person') border-red-500 @enderror"
                            onchange="toggleNewContact()">
                        <option value="">انتخاب کنید</option>
                        @foreach($project->contacts as $contact)
                            <option value="{{ $contact->name }}" {{ old('contact_person') == $contact->name ? 'selected' : '' }}>
                                {{ $contact->name }} {{ $contact->mobile ? '- ' . $contact->mobile : '' }}
                                @if($contact->position) ({{ $contact->position }}) @endif
                            </option>
                        @endforeach
                        <option value="new" {{ old('contact_person') == 'new' ? 'selected' : '' }}>➕ جدید</option>
                    </select>
                </div>
                @error('contact_person')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- فیلدهای مخاطب جدید -->
            <div id="new_contact_fields" class="border rounded-lg p-4 mb-4 {{ old('contact_person') == 'new' ? '' : 'hidden' }}">
                <h4 class="font-bold mb-2">اطلاعات مخاطب جدید</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm">نام کامل <span class="text-red-500">*</span></label>
                        <input type="text" name="new_contact_name" value="{{ old('new_contact_name') }}" 
                               class="w-full border rounded-lg px-4 py-2 @error('new_contact_name') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm">شماره موبایل</label>
                        <input type="text" name="new_contact_mobile" value="{{ old('new_contact_mobile') }}" 
                               class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm">سمت</label>
                        <input type="text" name="new_contact_position" value="{{ old('new_contact_position') }}" 
                               class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">نتیجه تماس <span class="text-red-500">*</span></label>
                <textarea name="result" rows="3" class="w-full border rounded-lg px-4 py-2 @error('result') border-red-500 @enderror" required>{{ old('result') }}</textarea>
                @error('result')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block mb-2 font-medium">تاریخ تماس بعدی</label>
                    <input type="text" id="next_call_date_display" value="{{ old('next_call_date_shamsi') }}" 
                           onclick="showNextCallDateModal()" readonly 
                           class="w-full border rounded-lg px-4 py-2 cursor-pointer bg-gray-50">
                    <input type="hidden" name="next_call_date_shamsi" id="next_call_date_shamsi" value="{{ old('next_call_date_shamsi') }}">
                    <input type="hidden" name="next_call_date" id="next_call_date_gregorian" value="{{ old('next_call_date') }}">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-medium">یادداشت</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    📝 ثبت تماس
                </button>
                <a href="{{ route('projects.show', $project) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal تقویم برای تاریخ تماس -->
<div id="callDateModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">انتخاب تاریخ و زمان تماس</h3>
        
        <button onclick="setNow()" class="w-full bg-green-600 text-white py-3 rounded-2xl mb-4 hover:bg-green-700 transition">
            📍 هم اکنون
        </button>
        
        <hr class="my-4">
        
        <div class="grid grid-cols-3 gap-4">
            <select id="call_year" onchange="updateCallDays()" class="border rounded-2xl p-4"></select>
            <select id="call_month" onchange="updateCallDays()" class="border rounded-2xl p-4"></select>
            <select id="call_day" class="border rounded-2xl p-4"></select>
        </div>
        <div class="mt-4">
            <label class="block mb-2">ساعت</label>
            <input type="time" id="call_time" value="09:00" class="w-full border rounded-2xl p-4">
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideCallDateModal()" class="flex-1 py-4 border rounded-2xl">انصراف</button>
            <button onclick="selectCallDate()" class="flex-1 bg-green-600 text-white py-4 rounded-2xl">تأیید</button>
        </div>
    </div>
</div>

<!-- Modal تقویم برای تاریخ تماس بعدی -->
<div id="nextCallDateModal" class="fixed inset-0 bg-black/70 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
        <h3 class="text-2xl font-bold text-center mb-6">انتخاب تاریخ تماس بعدی</h3>
        
        <button onclick="setTomorrow()" class="w-full bg-green-600 text-white py-3 rounded-2xl mb-4 hover:bg-green-700 transition">
            📍 فردا
        </button>
        
        <hr class="my-4">
        
        <div class="grid grid-cols-3 gap-4">
            <select id="next_year" onchange="updateNextDays()" class="border rounded-2xl p-4"></select>
            <select id="next_month" onchange="updateNextDays()" class="border rounded-2xl p-4"></select>
            <select id="next_day" class="border rounded-2xl p-4"></select>
        </div>
        <div class="flex gap-4 mt-8">
            <button onclick="hideNextCallDateModal()" class="flex-1 py-4 border rounded-2xl">انصراف</button>
            <button onclick="selectNextCallDate()" class="flex-1 bg-green-600 text-white py-4 rounded-2xl">تأیید</button>
        </div>
    </div>
</div>

<script>
function toggleNewContact() {
    const select = document.getElementById('contact_person');
    const fields = document.getElementById('new_contact_fields');
    if (select.value === 'new') {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}

function setNow() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth() + 1;
    const day = now.getDate();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const jalali = window.jalaali ? window.jalaali.toJalaali(year, month, day) : jalaali.toJalaali(year, month, day);
    const shamsi = `${jalali.jy}/${String(jalali.jm).padStart(2, '0')}/${String(jalali.jd).padStart(2, '0')}`;
    const shamsiWithTime = `${shamsi} ${hours}:${minutes}`;

    document.getElementById('call_date_display').value = shamsiWithTime;
    document.getElementById('call_date_shamsi').value = shamsiWithTime;
    
    const gregorian = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')} ${hours}:${minutes}:00`;
    document.getElementById('call_date_gregorian').value = gregorian;

    hideCallDateModal();
}

function setTomorrow() {
    const now = new Date();
    now.setDate(now.getDate() + 1);
    
    const year = now.getFullYear();
    const month = now.getMonth() + 1;
    const day = now.getDate();

    const jalali = window.jalaali ? window.jalaali.toJalaali(year, month, day) : jalaali.toJalaali(year, month, day);
    const shamsi = `${jalali.jy}/${String(jalali.jm).padStart(2, '0')}/${String(jalali.jd).padStart(2, '0')}`;

    document.getElementById('next_call_date_display').value = shamsi;
    document.getElementById('next_call_date_shamsi').value = shamsi;
    
    const gregorian = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    document.getElementById('next_call_date_gregorian').value = gregorian;

    hideNextCallDateModal();
}

function showCallDateModal() {
    document.getElementById('callDateModal').classList.remove('hidden');
    populateCallCalendar();
}

function hideCallDateModal() {
    document.getElementById('callDateModal').classList.add('hidden');
}

function populateCallCalendar() {
    const currentJYear = new Date().getFullYear() - 621;
    const yearSelect = document.getElementById('call_year');
    yearSelect.innerHTML = '';
    for (let y = currentJYear - 5; y <= currentJYear + 5; y++) {
        let opt = new Option(y, y);
        if (y === currentJYear) opt.selected = true;
        yearSelect.add(opt);
    }
    const months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const monthSelect = document.getElementById('call_month');
    monthSelect.innerHTML = '';
    months.forEach((name, i) => monthSelect.add(new Option(name, i+1)));
    updateCallDays();
}

function updateCallDays() {
    const daySelect = document.getElementById('call_day');
    daySelect.innerHTML = '';
    for (let d = 1; d <= 31; d++) daySelect.add(new Option(d, d));
}

function selectCallDate() {
    const y = parseInt(document.getElementById('call_year').value);
    const m = parseInt(document.getElementById('call_month').value);
    const d = document.getElementById('call_day').value.padStart(2, '0');
    const time = document.getElementById('call_time').value;

    const shamsi = `${y}/${m.toString().padStart(2, '0')}/${d}`;
    const shamsiWithTime = `${shamsi} ${time}`;

    document.getElementById('call_date_display').value = shamsiWithTime;
    document.getElementById('call_date_shamsi').value = shamsiWithTime;

    let gy = y + 621;
    let gm = m <= 6 ? m + 3 : m - 9;
    if (m > 6) gy++;

    const gregorian = `${gy}-${gm.toString().padStart(2, '0')}-${d} ${time}:00`;
    document.getElementById('call_date_gregorian').value = gregorian;

    hideCallDateModal();
}

function showNextCallDateModal() {
    document.getElementById('nextCallDateModal').classList.remove('hidden');
    populateNextCalendar();
}

function hideNextCallDateModal() {
    document.getElementById('nextCallDateModal').classList.add('hidden');
}

function populateNextCalendar() {
    const currentJYear = new Date().getFullYear() - 621;
    const yearSelect = document.getElementById('next_year');
    yearSelect.innerHTML = '';
    for (let y = currentJYear - 5; y <= currentJYear + 5; y++) {
        let opt = new Option(y, y);
        if (y === currentJYear) opt.selected = true;
        yearSelect.add(opt);
    }
    const months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const monthSelect = document.getElementById('next_month');
    monthSelect.innerHTML = '';
    months.forEach((name, i) => monthSelect.add(new Option(name, i+1)));
    updateNextDays();
}

function updateNextDays() {
    const daySelect = document.getElementById('next_day');
    daySelect.innerHTML = '';
    for (let d = 1; d <= 31; d++) daySelect.add(new Option(d, d));
}

function selectNextCallDate() {
    const y = parseInt(document.getElementById('next_year').value);
    const m = parseInt(document.getElementById('next_month').value);
    const d = document.getElementById('next_day').value.padStart(2, '0');

    const shamsi = `${y}/${m.toString().padStart(2, '0')}/${d}`;

    document.getElementById('next_call_date_display').value = shamsi;
    document.getElementById('next_call_date_shamsi').value = shamsi;

    let gy = y + 621;
    let gm = m <= 6 ? m + 3 : m - 9;
    if (m > 6) gy++;

    const gregorian = `${gy}-${gm.toString().padStart(2, '0')}-${d}`;
    document.getElementById('next_call_date_gregorian').value = gregorian;

    hideNextCallDateModal();
}

document.addEventListener('DOMContentLoaded', function() {
    toggleNewContact();
});
</script>
@endsection