@extends('layouts.simple')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-red-600">تست ساده پروژه‌ها</h1>
    
    <div class="bg-yellow-100 p-4 rounded my-4">
        تعداد پروژه‌ها: {{ $projects->count() }}
    </div>

    @foreach($projects as $project)
        <div class="border p-3 my-2 bg-white rounded">
            <strong>{{ $project->id }}</strong> - {{ $project->title }}
            ({{ $project->region ?? 'بدون منطقه' }})
        </div>
    @endforeach
</div>
@endsection