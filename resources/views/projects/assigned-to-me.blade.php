@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-purple-600">📋 لیدهای ارجاع داده شده به من</h1>
        <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl font-medium transition">
            🔙 بازگشت
        </a>
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

    <div class="bg-purple-50 border border-purple-200 p-4 rounded-2xl mb-6">
        <p class="text-purple-800">📌 لیدهایی که به شما ارجاع داده شده و در انتظار تایید یا در حال پیگیری هستند.</p>
    </div>

    @if($projects->count() > 0)
    <div class="bg-white shadow rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">نام پروژه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">بازاریاب</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">تاریخ بازدید</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">منطقه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">سطح</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">وضعیت ارجاع</th>
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
                        <td class="px-6 py-5">
                            @php
                                $assignment = $project->assignments()->where('assigned_to', auth()->id())->first();
                                $statusMap = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'accepted' => 'bg-blue-100 text-blue-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-green-100 text-green-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'در انتظار تایید',
                                    'accepted' => 'تایید شده',
                                    'rejected' => 'رد شده',
                                    'completed' => 'انجام شده'
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusMap[$assignment->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$assignment->status ?? 'pending'] ?? $assignment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 hover:underline ml-2">جزئیات</a>
                            @if($assignment && $assignment->status === 'pending')
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <form action="{{ route('assignments.update-status', $assignment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg transition text-xs font-medium">
                                            ✅ تایید
                                        </button>
                                    </form>
                                    <form action="{{ route('assignments.update-status', $assignment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition text-xs font-medium">
                                            ❌ رد
                                        </button>
                                    </form>
                                </div>
                            @elseif($assignment && $assignment->status === 'accepted')
                                <span class="text-green-600 text-xs font-medium">✅ تایید شده</span>
                            @elseif($assignment && $assignment->status === 'rejected')
                                <span class="text-red-600 text-xs font-medium">❌ رد شده</span>
                            @elseif($assignment && $assignment->status === 'completed')
                                <span class="text-gray-600 text-xs font-medium">✓ انجام شده</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $projects->links() }}
    </div>
    @else
    <div class="bg-white shadow rounded-3xl p-12 text-center">
        <span class="text-6xl">📭</span>
        <h2 class="text-2xl font-bold text-gray-600 mt-4">هیچ لیدی به شما ارجاع داده نشده است.</h2>
        <p class="text-gray-500 mt-2">مدیر فروش لیدها را به شما ارجاع خواهد داد.</p>
        <a href="{{ route('projects.index') }}" class="mt-6 inline-block bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-2xl font-medium transition">
            مشاهده همه لیدها
        </a>
    </div>
    @endif
</div>
@endsection