@extends('layouts.admin', ['heading' => $banner->exists ? 'Edit Banner' : 'Add Banner'])

@section('content')
    <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($banner->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Banner title"><input name="title" value="{{ old('title', $banner->title) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Banner title"></x-admin.field>
            <x-admin.field label="Placement"><input name="placement" value="{{ old('placement', $banner->placement ?? 'hero') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Placement"></x-admin.field>
            <x-admin.field label="Image URL" span><input name="image_url" value="{{ old('image_url', $banner->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Image URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload banner image<input type="file" name="image_file" accept="image/*" data-image-preview="banner-preview" data-image-preview-shape="wide" class="mt-2 block w-full"><x-admin.image-ratio-guide ratio="16:9" size="1920 x 1080 px" usage="Best for homepage hero and wide storefront banners." shape="wide" /></label>
            <div class="grid gap-2 md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Image preview</p>
                <div id="banner-preview" class="grid grid-cols-4 gap-2 md:grid-cols-6">
                    @if ($banner->image_url)
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="aspect-video rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.field label="Headline" span><input name="headline" value="{{ old('headline', $banner->headline) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Headline"></x-admin.field>
            <x-admin.field label="CTA label"><input name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="CTA label"></x-admin.field>
            <x-admin.field label="CTA URL"><input name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="CTA URL"></x-admin.field>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Banner</button>
        </div>
    </form>
@endsection
