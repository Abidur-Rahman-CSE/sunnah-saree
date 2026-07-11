@extends('layouts.admin', ['heading' => $collection->exists ? 'Edit Collection' : 'Add Collection'])

@section('content')
    <form action="{{ $collection->exists ? route('admin.collections.update', $collection) : route('admin.collections.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($collection->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Collection name"><input name="name" value="{{ old('name', $collection->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Collection name"></x-admin.field>
            <x-admin.field label="Slug"><input name="slug" value="{{ old('slug', $collection->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug"></x-admin.field>
            <x-admin.field label="Banner URL" span><input name="banner_url" value="{{ old('banner_url', $collection->banner_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Banner URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload collection banner<input type="file" name="banner_file" accept="image/*" data-image-preview="collection-preview" data-image-preview-shape="wide" class="mt-2 block w-full"><x-admin.image-ratio-guide ratio="16:9" size="1920 x 1080 px" usage="Best for collection cover cards and wide listing banners." shape="wide" /></label>
            <div class="grid gap-2 md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Banner preview</p>
                <div id="collection-preview" class="grid grid-cols-4 gap-2 md:grid-cols-6">
                    @if ($collection->banner_url)
                        <img src="{{ $collection->banner_url }}" alt="{{ $collection->name }}" class="aspect-video rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.field label="Description" span><textarea name="description" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Description">{{ old('description', $collection->description) }}</textarea></x-admin.field>
            <x-admin.product-picker
                :products="$products"
                :selected-ids="old('product_ids', $collection->products->pluck('id')->all())"
                title="Collection Products"
            />
            <x-admin.check label="Featured"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $collection->is_featured))></x-admin.check>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $collection->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Collection</button>
        </div>
    </form>
@endsection
