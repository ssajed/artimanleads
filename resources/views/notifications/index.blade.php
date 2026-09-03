@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">📬 نوتیفیکیشن‌ها</h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        @forelse($notifications as $notification)
        <div class="border-b hover:bg-gray-50 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
            <div class="px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium">{{ $notification->title }}</h3>
                        <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                        @if($notification->link)
                            <a href="{{ $notification->link }}" class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                                مشاهده
                            </a>
                        @endif
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($notification->created_at)->format('Y/m/d H:i') }}
                        @if(!$notification->is_read)
                            <form action="{{ route('notifications.mark-single-read', $notification) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:underline text-xs">خوانده شد</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-500">
            هیچ نوتیفیکیشنی وجود ندارد.
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection