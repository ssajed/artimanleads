@php
    if (!function_exists('translateBuildingType')) {
        function translateBuildingType($types) {
            $map = ['مسکونی' => 'مسکونی', 'اداری' => 'اداری', 'تجاری' => 'تجاری', 'هتل' => 'هتل', 'بیمارستان' => 'بیمارستان', 'مختلط' => 'مختلط'];
            if (is_array($types)) {
                $result = [];
                foreach ($types as $type) {
                    $result[] = $map[$type] ?? $type;
                }
                return implode('، ', $result);
            }
            return $map[$types] ?? $types;
        }
    }
    
    if (!function_exists('translateLevel')) {
        function translateLevel($level) {
            $map = ['A' => '🔥 داغ', 'B' => '⏳ پیگیری', 'C' => '🗄️ آرشیو'];
            return $map[$level] ?? $level;
        }
    }
    
    if (!function_exists('translatePurchaseStage')) {
        function translatePurchaseStage($stage) {
            $map = ['no_inquiry' => 'بدون استعلام', 'inquiry' => 'استعلام', 'negotiation' => 'مذاکره', 'purchased' => 'خرید شده'];
            return $map[$stage] ?? $stage;
        }
    }

    if (!function_exists('translateProjectStatus')) {
        function translateProjectStatus($status) {
            $map = [
                'lead' => '📝 لید',
                'assigned' => '🔄 ارجاع پرونده',
                'negotiation' => '🤝 در حال مذاکره',
                'sold' => '💰 فروخته شد',
                'archived' => '📁 بایگانی',
                'lost' => '❌ بازنده'
            ];
            return $map[$status] ?? $status;
        }
    }

    if (!function_exists('getStatusColor')) {
        function getStatusColor($status) {
            $map = [
                'lead' => 'bg-blue-100 text-blue-800',
                'assigned' => 'bg-purple-100 text-purple-800',
                'negotiation' => 'bg-yellow-100 text-yellow-800',
                'sold' => 'bg-green-100 text-green-800',
                'archived' => 'bg-gray-100 text-gray-800',
                'lost' => 'bg-red-100 text-red-800'
            ];
            return $map[$status] ?? 'bg-gray-100 text-gray-800';
        }
    }
@endphp

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800">جزئیات پروژه: {{ $project->title }}</h1>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('projects.edit', $project) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-2xl font-medium transition">
                ✏️ ویرایش
            </a>
            <a href="{{ route('projects.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl font-medium transition">
                🔙 بازگشت
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-2xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-5 py-4 rounded-2xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">

        <!-- ===== بخش تایید ارجاع برای کارشناس (فقط یک بار) ===== -->
        @if(auth()->user()->role === 'sales_expert')
            @php
                $pendingAssignment = $project->assignments()->where('assigned_to', auth()->id())->where('status', 'pending')->first();
            @endphp
            @if($pendingAssignment)
                <div class="bg-yellow-50 border border-yellow-400 rounded-2xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🔔</span>
                        <div class="flex-1">
                            <p class="text-yellow-800 font-bold">یک ارجاع جدید برای شما وجود دارد!</p>
                            <p class="text-yellow-700 text-sm mt-1">لید "{{ $project->title }}" توسط {{ $pendingAssignment->assignedBy->name }} به شما ارجاع داده شده است.</p>
                            <div class="flex gap-2 mt-3">
                                <form action="{{ route('assignments.update-status', $pendingAssignment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition text-sm font-medium">
                                        ✅ تایید ارجاع
                                    </button>
                                </form>
                                <form action="{{ route('assignments.update-status', $pendingAssignment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition text-sm font-medium">
                                        ❌ رد ارجاع
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $existingAssignment = $project->assignments()->where('assigned_to', auth()->id())->first();
                @endphp
                @if($existingAssignment && $existingAssignment->status === 'accepted')
                    <div class="bg-green-50 border border-green-400 rounded-2xl p-4 mb-4">
                        <p class="text-green-800">✅ شما این لید را قبلاً تایید کرده‌اید و در حال پیگیری آن هستید.</p>
                    </div>
                @elseif($existingAssignment && $existingAssignment->status === 'rejected')
                    <div class="bg-red-50 border border-red-400 rounded-2xl p-4 mb-4">
                        <p class="text-red-800">❌ شما این لید را رد کرده‌اید.</p>
                    </div>
                @endif
            @endif
        @endif

        <!-- کارت وضعیت پروژه -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6 border-r-4 border-blue-500">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-800">📊 وضعیت پروژه:</h3>
                    <span class="px-4 py-2 text-sm font-bold rounded-full {{ getStatusColor($project->project_status ?? 'lead') }}">
                        {{ translateProjectStatus($project->project_status ?? 'lead') }}
                    </span>
                </div>
                
                @if(in_array(auth()->user()->role, ['admin', 'sales_manager', 'sales_expert']))
                    @php
                        $user = auth()->user();
                        $canChangeStatus = false;
                        $allowedStatuses = [];
                        
                        if ($user->role === 'admin' || $user->role === 'sales_manager') {
                            $canChangeStatus = true;
                            $allowedStatuses = ['lead', 'assigned', 'negotiation', 'sold', 'archived', 'lost'];
                        } elseif ($user->role === 'sales_expert') {
                            $assignment = $project->assignments()->where('assigned_to', $user->id)->where('status', 'accepted')->first();
                            if ($assignment) {
                                $canChangeStatus = true;
                                $allowedStatuses = ['sold', 'archived', 'lost'];
                            }
                        }
                    @endphp
                    
                    @if($canChangeStatus)
                    <form action="{{ route('projects.update-status', $project) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border rounded-lg px-4 py-2 text-sm">
                            @foreach($allowedStatuses as $status)
                                <option value="{{ $status }}" {{ ($project->project_status ?? 'lead') == $status ? 'selected' : '' }}>
                                    {{ translateProjectStatus($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                            به‌روزرسانی
                        </button>
                    </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- کارت اطلاعات عمومی -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6 border-r-4 border-green-500">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">
                📋 اطلاعات پایه پروژه
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">نام پروژه:</span>
                    <span class="font-bold text-gray-900 text-lg">{{ $project->title }}</span>
                </div>
                
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">تاریخ بازدید:</span>
                    <span class="text-base">
                        @if($project->visit_date)
                            @php
                                try {
                                    $date = $project->visit_date;
                                    if (is_string($date)) {
                                        $date = \Carbon\Carbon::parse($date);
                                    }
                                    echo \Morilog\Jalali\Jalalian::fromCarbon($date)->format('Y/m/d');
                                } catch (\Exception $e) {
                                    echo 'تاریخ نامعتبر';
                                }
                            @endphp
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1 text-sm">منطقه:</span>
                    <span class="text-base">{{ $project->region ?? '-' }}</span>
                </div>
                
                <div class="md:col-span-2 lg:col-span-3">
                    <span class="text-gray-500 block mb-1 text-sm">آدرس دقیق:</span>
                    <span class="bg-gray-50 p-2 rounded block text-base">{{ $project->address }}</span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1 text-sm">کاربری:</span>
                    <span class="text-base">
                        @if($project->building_type)
                            @php
                                $types = is_array($project->building_type) ? $project->building_type : json_decode($project->building_type, true);
                            @endphp
                            @if(is_array($types))
                                @foreach($types as $type)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full ml-1">{{ $type }}</span>
                                @endforeach
                            @else
                                {{ $types }}
                            @endif
                        @else
                            -
                        @endif
                    </span>
                </div>
                
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">تعداد طبقات:</span>
                    <span class="text-base">{{ $project->floors ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">تعداد بلوک‌ها:</span>
                    <span class="text-base">{{ $project->blocks ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">بازاریاب:</span>
                    <span class="text-base">{{ $project->marketer_name ?? $project->user?->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">سطح پروژه:</span>
                    <span class="text-base">
                        @php
                            $levelClass = match($project->level) {
                                'A' => 'bg-red-100 text-red-800',
                                'B' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelClass }}">
                            {{ translateLevel($project->level) }}
                        </span>
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">امتیاز کل:</span>
                    <span class="text-base font-bold text-green-600">{{ $project->total_score ?? $project->score ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- کارت افراد کلیدی -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">👥 افراد کلیدی پروژه</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">سمت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موبایل</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($project->has_project_manager)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">مدیر پروژه</td>
                            <td class="px-6 py-4 text-sm">{{ $project->project_manager ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->project_manager_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if($project->has_site_manager)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">مدیر کارگاه</td>
                            <td class="px-6 py-4 text-sm">{{ $project->site_manager ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->site_manager_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if($project->has_facilities_supervisor)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">سرپرست تأسیسات</td>
                            <td class="px-6 py-4 text-sm">{{ $project->facilities_supervisor ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->facilities_supervisor_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if($project->has_purchasing_manager)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">مسئول خرید</td>
                            <td class="px-6 py-4 text-sm">{{ $project->purchasing_manager ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->purchasing_manager_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if($project->has_mechanical_consultant)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">مشاور مکانیک</td>
                            <td class="px-6 py-4 text-sm">{{ $project->mechanical_consultant ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->mechanical_consultant_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if($project->has_hvac_contractor)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">پیمانکار تأسیسات</td>
                            <td class="px-6 py-4 text-sm">{{ $project->hvac_contractor ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $project->hvac_contractor_mobile ?? '-' }}</td>
                        </tr>
                        @endif
                        @if(!$project->has_project_manager && !$project->has_site_manager && !$project->has_facilities_supervisor && !$project->has_purchasing_manager && !$project->has_mechanical_consultant && !$project->has_hvac_contractor)
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">هیچ فرد کلیدی ثبت نشده است.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- کارت مخاطبین پروژه -->
        @if($project->contacts && $project->contacts->count() > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">👤 مخاطبین پروژه</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موبایل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">سمت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->contacts as $contact)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $contact->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $contact->mobile ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $contact->position ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- کارت اطلاعات فنی -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">⚙️ اطلاعات فنی</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">چیلر دارد؟:</span>
                    <span class="text-base">{{ $project->has_chiller == 'yes' ? '✅ بله' : ($project->has_chiller == 'no' ? '❌ خیر' : '-') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">برند چیلر:</span>
                    <span class="text-base">{{ $project->chiller_brand ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">برج خنک‌کن دارد؟:</span>
                    <span class="text-base">{{ $project->has_cooling_tower == 'yes' ? '✅ بله' : ($project->has_cooling_tower == 'no' ? '❌ خیر' : '-') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">برند فعلی برج خنک‌کن:</span>
                    <span class="text-base">{{ $project->current_cooling_brand ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">ظرفیت تقریبی (TR):</span>
                    <span class="text-base">{{ $project->capacity_tr ?? '-' }}</span>
                </div>
                @if($project->chiller_photo)
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">عکس چیلر:</span>
                    <a href="{{ asset('storage/' . $project->chiller_photo) }}" target="_blank" class="text-blue-600 hover:underline">
                        📷 مشاهده عکس
                    </a>
                </div>
                @endif
                @if($project->cooling_tower_photo)
                <div>
                    <span class="text-gray-500 block mb-1 text-sm">عکس برج خنک‌کن:</span>
                    <a href="{{ asset('storage/' . $project->cooling_tower_photo) }}" target="_blank" class="text-blue-600 hover:underline">
                        📷 مشاهده عکس
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- کارت وضعیت خرید و رقبا -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">🛒 وضعیت خرید</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-500 block mb-1 text-sm">مرحله خرید:</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-1 text-sm">زمان احتمالی خرید:</span>
                        <span class="text-base">
                            @if($project->expected_purchase_date || $project->estimated_purchase_date)
                                @php
                                    try {
                                        $date = $project->expected_purchase_date ?? $project->estimated_purchase_date;
                                        if (is_string($date)) {
                                            $date = \Carbon\Carbon::parse($date);
                                        }
                                        echo \Morilog\Jalali\Jalalian::fromCarbon($date)->format('Y/m/d');
                                    } catch (\Exception $e) {
                                        echo '-';
                                    }
                                @endphp
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">🏢 رقبا</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-500 block mb-1 text-sm">برندهای رقبا:</span>
                        <span class="text-base">{{ $project->competitors ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-1 text-sm">فروشنده رقیب:</span>
                        <span class="text-base">{{ $project->competitor_seller ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-1 text-sm">قیمت تقریبی:</span>
                        <span class="text-base">{{ $project->approx_price ? number_format($project->approx_price) . ' تومان' : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- دکمه‌های عملیات -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">🔧 عملیات</h3>
            <div class="flex flex-wrap gap-4">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <a href="{{ route('assignments.create', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition">
                        🔄 ارجاع به کارشناس
                    </a>
                @endif
                
                @if(auth()->user()->role === 'sales_expert' || auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <a href="{{ route('call-logs.create', $project) }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg transition">
                        📞 ثبت تماس جدید
                    </a>
                @endif
                
                <a href="{{ route('call-logs.index', ['project_id' => $project->id]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg transition">
                    📋 مشاهده تماس‌ها
                </a>
            </div>
        </div>

        <!-- کارت ارجاع‌های این پروژه (فقط برای مدیران) -->
        @if((auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager') && $project->assignments && $project->assignments->count() > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">🔄 ارجاع‌های این پروژه</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">کارشناس</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ارجاع دهنده</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->assignments as $assignment)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $assignment->assignedTo->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $assignment->assignedBy->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusMap = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'accepted' => 'bg-blue-100 text-blue-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-green-100 text-green-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'در انتظار',
                                        'accepted' => 'پذیرفته شده',
                                        'rejected' => 'رد شده',
                                        'completed' => 'انجام شده'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusMap[$assignment->status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabels[$assignment->status] ?? $assignment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ \Morilog\Jalali\Jalalian::fromCarbon($assignment->created_at)->format('Y/m/d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- کارت تماس‌های این پروژه -->
        @if($project->callLogs && $project->callLogs->count() > 0)
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">📞 آخرین تماس‌ها</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">کارشناس</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موضوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نتیجه</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ تماس</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تماس بعدی</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->callLogs->take(10) as $log)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $log->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $log->subject }}</td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($log->result, 30) }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->call_date)
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($log->call_date)->format('Y/m/d H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->next_call_date)
                                    @php
                                        $nextDate = \Carbon\Carbon::parse($log->next_call_date);
                                        $isToday = $nextDate->isToday();
                                        $isTomorrow = $nextDate->isTomorrow();
                                        $isPast = $nextDate->isPast();
                                    @endphp
                                    <span class="{{ $isPast ? 'text-red-600 font-bold' : ($isToday ? 'text-yellow-600 font-bold' : '') }}">
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($nextDate)->format('Y/m/d') }}
                                        @if($isToday) 🔔 امروز @endif
                                        @if($isTomorrow) ⏰ فردا @endif
                                        @if($isPast) ⚠️ گذشته @endif
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($project->callLogs->count() > 10)
                <div class="mt-3 text-center">
                    <a href="{{ route('call-logs.index', ['project_id' => $project->id]) }}" class="text-blue-600 hover:underline">
                        مشاهده همه تماس‌ها ({{ $project->callLogs->count() }})
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- کارت یادداشت‌ها -->
        <div class="bg-white overflow-hidden shadow-lg rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">📝 یادداشت‌ها</h3>
            <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $project->notes ?? 'یادداشتی ثبت نشده است.' }}</p>
        </div>
    </div>
</div>
@endsection