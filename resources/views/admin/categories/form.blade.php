@extends('layouts.admin', ['heading' => $category->exists ? 'Edit Category' : 'Add Category'])

@section('content')
    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <div class="grid gap-4">
            <x-admin.field label="Category name"><input name="name" value="{{ old('name', $category->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Category name"></x-admin.field>
            <x-admin.field label="Slug"><input name="slug" value="{{ old('slug', $category->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug"></x-admin.field>
            <x-admin.field label="Parent category"><select name="parent_id" class="rounded-lg border border-[#ddd4c4] px-4 py-3"><option value="">Root category</option>@foreach ($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>@endforeach</select></x-admin.field>
            <x-admin.field label="Image URL"><input name="image_url" value="{{ old('image_url', $category->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Image URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm">Upload category image<input type="file" name="image_file" accept="image/*" data-image-preview="category-preview" data-image-preview-shape="square" class="mt-2 block w-full"><x-admin.image-ratio-guide ratio="1:1" size="1200 x 1200 px" usage="Best for category tiles and compact image cards." /></label>
            <div class="grid gap-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Image preview</p>
                <div id="category-preview" class="grid grid-cols-4 gap-2">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="aspect-square rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))></x-admin.check>
            <x-admin.check label="Featured"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $category->is_featured))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Save Category</button>
        </div>
    </form>
@endsection
