@extends('layouts.admin', ['heading' => 'Collections'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.collections.create')" create-label="Add Collection" search-placeholder="Search collections" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Image</th><th>Name</th><th>Products</th><th>Featured</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($collections as $collection)
                    <tr class="border-b">
                        <td class="p-3">
                            @if ($collection->banner_url)
                                <img src="{{ $collection->banner_url }}" alt="{{ $collection->name }}" class="h-14 w-20 rounded-lg border border-[#eadcc3] object-cover">
                            @else
                                <span class="flex h-14 w-20 items-center justify-center rounded-lg border border-[#eadcc3] bg-[#faf8f3] text-xs text-[#8d786d]">No image</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ $collection->name }}</td><td>{{ $collection->products_count }}</td><td>{{ $collection->is_featured ? 'Yes' : 'No' }}</td><td>{{ $collection->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.collections.edit', $collection) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $collections->links() }}</div>
@endsection
