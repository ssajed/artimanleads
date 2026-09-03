@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">لیست ارجاع‌ها</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right">پروژه</th>
                    <th class="px-6 py-3 text-right">ارجاع دهنده</th>
                    <th class="px-6 py-3 text-right">کارشناس</th>
                    <th class="px-6 py-3 text-right">وضعیت</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('projects.show', $assignment->project) }}" class="text-blue-600 hover:underline">
                            {{ $assignment->project->title }}
                        </a>
                    </td>
                    <td class="px-6 py-4">{{ $assignment->assignedBy->name }}</td>
                    <td class="px-6 py-4">{{ $assignment->assignedTo->name }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusMap = [
                                'pending' => ['bg-yellow-100 text-yellow-800', 'در انتظار'],
                                'accepted' => ['bg-blue-100 text-blue-800', 'پذیرفته شده'],
                                'rejected' => ['bg-red-100 text-red-800', 'رد شده'],
                                'completed' => ['bg-green-100 text-green-800', 'انجام شده']
                            ];
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusMap[$assignment->status][0] ?? 'bg-gray-100' }}">
                            {{ $statusMap[$assignment->status][1] ?? $assignment->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if(auth()->user()->id === $assignment->assigned_to && $assignment->status === 'pending')
                            <div class="flex gap-2">
                                <form action="{{ route('assignments.update-status', $assignment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 text-sm">
                                        تایید
                                    </button>
                                </form>
                                <form action="{{ route('assignments.update-status', $assignment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 text-sm">
                                        رد
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">هیچ ارجاعی وجود ندارد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $assignments->links() }}
    </div>
</div>
@endsection