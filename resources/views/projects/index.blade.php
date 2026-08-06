@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📋 لیست پروژه‌ها / لیدها</h1>
        <div class="flex gap-3 flex-wrap">
            @if(auth()->user()->role === 'marketer')
                <a href="{{ route('projects.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 {{ !request()->has('filter') ? 'bg-blue-700' : '' }}">
                    لیدهای من
                </a>
            @endif
            @if(auth()->user()->role === 'sales_expert')
                <a href="{{ route('projects.index', ['filter' => 'assigned']) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 {{ request('filter') == 'assigned' ? 'bg-purple-700' : '' }}">
                    پروژه‌های محول شده
                </a>
            @endif
            <a href="{{ route('projects.select-type') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-medium transition">
                + ثبت بازدید جدید
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-2xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->role === 'marketer')
        <div class="bg-blue-50 border border-blue-400 text-blue-800 px-4 py-3 rounded-2xl mb-4">
            📌 شما فقط لیدهای اختصاصی خود را مشاهده می‌کنید.
        </div>
    @endif
    @if(auth()->user()->role === 'sales_expert')
        <div class="bg-purple-50 border border-purple-400 text-purple-800 px-4 py-3 rounded-2xl mb-4">
            📌 شما فقط پروژه‌های محول شده به خود را مشاهده می‌کنید.
        </div>
    @endif

    {{-- نمایش فیلتر فعال --}}
    @if(isset($filter) && !empty($filter))
        <div class="bg-blue-50 p-4 rounded-2xl mb-6 flex justify-between items-center flex-wrap gap-2">
            <div>
                <span class="font-bold">فیلتر فعال:</span>
                @if(isset($filter['level']))
                    <span class="bg-blue-200 px-3 py-1 rounded-full text-sm ml-2">
                        سطح: {{ $filter['level'] == 'A' ? '🔥 داغ' : ($filter['level'] == 'B' ? '⏳ پیگیری' : '🗄️ آرشیو') }}
                    </span>
                @endif
                @if(isset($filter['user_id']))
                    <span class="bg-blue-200 px-3 py-1 rounded-full text-sm ml-2">
                        کاربر: {{ \App\Models\User::find($filter['user_id'])?->name ?? 'نامشخص' }}
                    </span>
                @endif
            </div>
            <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                حذف فیلتر ✕
            </a>
        </div>
    @endif

    <div class="bg-blue-50 p-4 rounded-2xl mb-6">
        <span class="font-bold">تعداد کل پروژه‌ها: {{ $projects->total() }}</span>
    </div>

    @if($projects->count() > 0)
    <div class="bg-white shadow rounded-3xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">نام پروژه</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">بازاریاب</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">تاریخ بازدید</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">منطقه</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">سطح</th>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">وضعیت ارجاع</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">ارجاع به</th>
                    @endif
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($projects as $project)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-5 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-6 py-5">
                        <div class="font-medium text-gray-900">{{ $project->title }}</div>
                        <div class="text-sm text-gray-500">{{ $project->address ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5 text-sm text-gray-700">
                        {{ $project->marketer_name ?? $project->user?->name ?? 'نامشخص' }}
                    </td>
                    <td class="px-6 py-5 text-sm text-gray-700">
                        @if($project->visit_date)
                            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($project->visit_date))->format('Y/m/d') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-5 text-sm text-gray-700">{{ $project->region ?? '-' }}</td>
                    <td class="px-6 py-5">
                        @php
                            $level = $project->project_level ?? 'B_followup';
                            $levelMap = [
                                'A_hot' => ['bg-red-100 text-red-800', '🔥 داغ'],
                                'B_followup' => ['bg-yellow-100 text-yellow-800', '⏳ پیگیری'],
                                'C_archive' => ['bg-gray-100 text-gray-800', '🗄️ آرشیو']
                            ];
                            $levelInfo = $levelMap[$level] ?? ['bg-gray-100 text-gray-800', $level];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelInfo[0] }}">
                            {{ $levelInfo[1] }}
                        </span>
                    </td>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <td class="px-6 py-5 text-sm">
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
                        @if($project->latestAssignment)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusMap[$project->latestAssignment->status] ?? 'bg-gray-100' }}">
                                {{ $statusLabels[$project->latestAssignment->status] ?? $project->latestAssignment->status }}
                            </span>
                        @else
                            <span class="text-gray-400">ارجاع نشده</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-sm">
                        {{ $project->latestAssignment?->assignedTo?->name ?? '-' }}
                    </td>
                    @endif
                    <td class="px-6 py-5 text-sm">
                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 hover:underline ml-2">جزئیات</a>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                            <a href="{{ route('projects.edit', $project) }}" class="text-green-600 hover:text-green-800 hover:underline ml-2">ویرایش</a>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block ml-2" 
                                  onsubmit="return confirm('آیا از حذف پروژه {{ $project->title }} مطمئن هستید؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">حذف</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $projects->links() }}
    </div>
    @else
    <div class="bg-white shadow rounded-3xl p-12 text-center">
        <span class="text-6xl">📭</span>
        <h2 class="text-2xl font-bold text-gray-600 mt-4">
            @if(auth()->user()->role === 'marketer')
                هیچ لیدی برای شما ثبت نشده است.
            @elseif(auth()->user()->role === 'sales_expert')
                هیچ پروژه‌ای به شما محول نشده است.
            @else
                هیچ پروژه‌ای با این فیلتر یافت نشد.
            @endif
        </h2>
        <p class="text-gray-500 mt-2">فیلتر را حذف کنید یا پروژه جدید ثبت کنید.</p>
        <a href="{{ route('projects.index') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-medium transition">
            حذف فیلتر
        </a>
    </div>
    @endif
</div>
@endsection