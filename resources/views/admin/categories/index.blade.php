@extends('layouts.admin', ['heading' => 'Categories'])

@section('content')
    <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <form class="grid gap-2 md:grid-cols-[1fr_160px_auto]">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="Search categories">
            <select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-2"><option value="">Any status</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select>
            <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-center font-semibold text-white">Add Category</a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Name</th><th>Parent</th><th>Featured</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach ($categories as $category)
                <tr class="border-b"><td class="p-3 font-semibold">{{ $category->name }}</td><td>{{ $category->parent?->name ?? 'Root' }}</td><td>{{ $category->is_featured ? 'Yes' : 'No' }}</td><td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td><td><a class="text-[#7a1f55] font-semibold" href="{{ route('admin.categories.edit', $category) }}">Edit</a></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $categories->links() }}</div>
@endsection
