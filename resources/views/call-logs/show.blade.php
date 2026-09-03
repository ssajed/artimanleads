@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📞 جزئیات تماس</h1>
        <a href="{{ route('call-logs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            🔙 بازگشت
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">پروژه</p>
                <p class="font-medium">
                    <a href="{{ route('projects.show', $callLog->project) }}" class="text-blue-600 hover:underline">
                        {{ $callLog->project->title }}
                    </a>
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">کارشناس</p>
                <p class="font-medium">{{ $callLog->user->name }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">زمان تماس</p>
                <p class="font-medium">{{ \Morilog\Jalali\Jalalian::fromCarbon($callLog->call_date)->format('Y/m/d H:i') }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">موضوع تماس</p>
                <p class="font-medium">{{ $callLog->subject }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">شخص تماس گیرنده</p>
                <p class="font-medium">{{ $callLog->contact_person }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">تاریخ تماس بعدی</p>
                <p class="font-medium">
                    @if($callLog->next_call_date)
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($callLog->next_call_date)->format('Y/m/d') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">نتیجه تماس</p>
            <p class="font-medium whitespace-pre-line">{{ $callLog->result }}</p>
        </div>

        @if($callLog->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">یادداشت</p>
            <p class="font-medium whitespace-pre-line">{{ $callLog->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection