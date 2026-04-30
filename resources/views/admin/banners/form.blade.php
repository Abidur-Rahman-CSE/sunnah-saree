@extends('layouts.admin', ['heading' => $banner->exists ? 'Edit Banner' : 'Add Banner'])

@section('content')
    <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($banner->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <input name="title" value="{{ old('title', $banner->title) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Banner title">
            <input name="placement" value="{{ old('placement', $banner->placement ?? 'hero') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Placement">
            <input name="image_url" value="{{ old('image_url', $banner->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Image URL">
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload banner image<input type="file" name="image_file" accept="image/*" class="mt-2 block w-full"></label>
            <input name="headline" value="{{ old('headline', $banner->headline) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Headline">
            <input name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="CTA label">
            <input name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="CTA URL">
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))> Active</label>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Banner</button>
        </div>
    </form>
@endsection
