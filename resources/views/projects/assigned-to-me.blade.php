@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">📋 پروژه‌های محول شده به من</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-5 py-4 rounded-2xl mb-6 transition-colors">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام پروژه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">منطقه</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">سطح</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت ارجاع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ ارجاع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($projects ?? [] as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $project->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $project->region ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300">{{ $project->region ?? '-' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $levelClass = match($project->level) {
                                    'A' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    'B' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $levelClass }}">
                                {{ translateLevel($project->level) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $assignment = $project->assignments()->where('assigned_to', auth()->id())->first();
                                $statusMap = [
                                    'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                    'accepted' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                    'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                ];
                                $statusLabels = [
                                    'pending' => 'در انتظار',
                                    'accepted' => 'پذیرفته شده',
                                    'rejected' => 'رد شده',
                                    'completed' => 'انجام شده'
                                ];
                            @endphp
                            @if($assignment)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusMap[$assignment->status] ?? 'bg-gray-100 dark:bg-gray-700' }}">
                                    {{ $statusLabels[$assignment->status] ?? $assignment->status }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            {{ $assignment ? \Morilog\Jalali\Jalalian::fromCarbon($assignment->created_at)->format('Y/m/d') : '-' }}
                        </td>
                        <td class="px-6 py-5 text-sm whitespace-nowrap">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">مشاهده جزئیات</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            هیچ پروژه‌ای به شما محول نشده است.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection