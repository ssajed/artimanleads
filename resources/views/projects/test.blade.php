@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold">تست پروژه‌ها</h1>
    <p>تعداد پروژه‌ها: {{ $projects->count() }}</p>
    <ul>
    @foreach($projects as $project)
        <li>{{ $project->id }} - {{ $project->title }}</li>
    @endforeach
    </ul>
</div>
@endsection