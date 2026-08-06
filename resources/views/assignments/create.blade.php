@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">🔄 ارجاع لید به کارشناس</h1>
    
    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <p><strong>پروژه:</strong> {{ $project->title }}</p>
            <p><strong>آدرس:</strong> {{ $project->address }}</p>
            <p><strong>منطقه:</strong> {{ $project->region }}</p>
        </div>

        <form action="{{ route('assignments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="mb-4">
                <label class="block mb-2 font-medium">کارشناس فروش <span class="text-red-500">*</span></label>
                <select name="assigned_to" class="w-full border rounded-lg px-4 py-2 @error('assigned_to') border-red-500 @enderror" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($experts as $expert)
                        <option value="{{ $expert->id }}" {{ old('assigned_to') == $expert->id ? 'selected' : '' }}>
                            {{ $expert->name }} ({{ $expert->email }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">یادداشت</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    📤 ثبت ارجاع
                </button>
                <a href="{{ route('projects.show', $project) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>
@endsection