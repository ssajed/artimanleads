@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📞 انتخاب لید برای ثبت تماس</h1>
        <a href="{{ route('call-logs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            🔙 بازگشت
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-4">
            <label class="block mb-2 font-medium">جستجو در لیدها</label>
            <input type="text" id="searchProject" placeholder="نام پروژه یا آدرس..." 
                   class="w-full border rounded-lg px-4 py-2" onkeyup="filterProjects()">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right">نام پروژه</th>
                        <th class="px-6 py-3 text-right">آدرس</th>
                        <th class="px-6 py-3 text-right">منطقه</th>
                        <th class="px-6 py-3 text-right">عملیات</th>
                    </tr>
                </thead>
                <tbody id="projectList">
                    @forelse($projects as $project)
                    <tr class="border-t hover:bg-gray-50 project-row">
                        <td class="px-6 py-4">{{ $project->title }}</td>
                        <td class="px-6 py-4">{{ Str::limit($project->address, 30) }}</td>
                        <td class="px-6 py-4">{{ $project->region ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('call-logs.create', $project) }}" 
                               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                📞 ثبت تماس
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            هیچ لیدی برای ثبت تماس وجود ندارد.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>

<script>
function filterProjects() {
    const input = document.getElementById('searchProject');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.project-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}
</script>
@endsection