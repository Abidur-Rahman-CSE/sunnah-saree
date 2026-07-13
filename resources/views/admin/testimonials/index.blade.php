@extends('layouts.admin', ['heading' => 'Testimonials'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.testimonials.create')" create-label="Add Testimonial" search-placeholder="Search testimonials" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Image</th><th>Name</th><th>Message</th><th>Link</th><th>Sort</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($testimonials as $testimonial)
                    <tr class="border-b">
                        <td class="p-3">
                            @if ($testimonial->image_url)
                                <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->customer_name }}" class="h-14 w-20 rounded-lg border border-[#eadcc3] object-cover">
                            @else
                                <span class="flex h-14 w-20 items-center justify-center rounded-lg border border-[#eadcc3] bg-[#faf8f3] text-xs text-[#8d786d]">No image</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ $testimonial->customer_name }}</td>
                        <td class="max-w-sm truncate">{{ $testimonial->message }}</td>
                        <td>
                            @if ($testimonial->facebook_post_url)
                                <a href="{{ $testimonial->facebook_post_url }}" target="_blank" rel="noopener" class="font-semibold text-[#7a1f55]">Open</a>
                            @else
                                <span class="text-[#8d786d]">No link</span>
                            @endif
                        </td>
                        <td>{{ $testimonial->sort_order }}</td>
                        <td>{{ $testimonial->is_active ? 'Active' : 'Inactive' }}</td>
                        <td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.testimonials.edit', $testimonial) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $testimonials->links() }}</div>
@endsection
