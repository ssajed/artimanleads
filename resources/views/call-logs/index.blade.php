@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">📞 لیست تماس‌ها و مکاتبات</h1>
        <a href="{{ route('call-logs.select-project') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-medium transition">
            📞 ثبت تماس جدید
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- جستجو و فیلتر --}}
    <div class="bg-white p-4 rounded-xl shadow mb-4">
        <form action="{{ route('call-logs.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">جستجو</label>
                <input type="text" name="search" value="{{ $searchQuery ?? '' }}" 
                       placeholder="جستجو در موضوع، شخص، نتیجه، پروژه..." 
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مرتب‌سازی</label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('call-logs.index', array_merge(request()->all(), ['sort' => 'call_date', 'order' => 'desc'])) }}" 
                       class="px-3 py-1.5 rounded-lg text-sm {{ $sortParams['sort'] == 'call_date' && $sortParams['order'] == 'desc' ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        جدیدترین
                    </a>
                    <a href="{{ route('call-logs.index', array_merge(request()->all(), ['sort' => 'call_date', 'order' => 'asc'])) }}" 
                       class="px-3 py-1.5 rounded-lg text-sm {{ $sortParams['sort'] == 'call_date' && $sortParams['order'] == 'asc' ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        قدیمی‌ترین
                    </a>
                    <a href="{{ route('call-logs.index', array_merge(request()->all(), ['sort' => 'next_call_date', 'order' => 'asc'])) }}" 
                       class="px-3 py-1.5 rounded-lg text-sm {{ $sortParams['sort'] == 'next_call_date' && $sortParams['order'] == 'asc' ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                        نزدیک‌ترین تماس بعدی
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg transition text-sm">
                        🔍 جستجو
                    </button>
                    <a href="{{ route('call-logs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-1.5 rounded-lg transition text-sm">
                        ✕ پاک کردن
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">پروژه</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">کارشناس</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">زمان تماس</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موضوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نتیجه</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($callLogs as $log)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('projects.show', $log->project) }}" class="text-blue-600 hover:underline">
                                {{ $log->project->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $log->user->name }}</td>
                        <td class="px-6 py-4">
                            @if($log->call_date)
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($log->call_date)->format('Y/m/d H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $log->subject }}</td>
                        <td class="px-6 py-4">{{ Str::limit($log->result, 50) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('call-logs.show', $log) }}" class="text-blue-600 hover:underline">جزئیات</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            @if(!empty($searchQuery))
                                هیچ تماسی با عبارت "{{ $searchQuery }}" یافت نشد.
                            @else
                                هیچ تماسی ثبت نشده است.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $callLogs->appends(request()->query())->links() }}
    </div>
</div>
@endsection