@extends('layouts.admin', ['heading' => 'Banners'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.banners.create')" create-label="Add Banner" search-placeholder="Search banners" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Title</th><th>Placement</th><th>Headline</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($banners as $banner)
                    <tr class="border-b"><td class="p-3 font-semibold">{{ $banner->title }}</td><td>{{ $banner->placement }}</td><td>{{ $banner->headline }}</td><td>{{ $banner->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.banners.edit', $banner) }}">Edit</a></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $banners->links() }}</div>
@endsection
