@extends('layouts.admin', ['heading' => $category->exists ? 'Edit Category' : 'Add Category'])

@section('content')
    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <div class="grid gap-4">
            <input name="name" value="{{ old('name', $category->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Category name">
            <input name="slug" value="{{ old('slug', $category->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug">
            <select name="parent_id" class="rounded-lg border border-[#ddd4c4] px-4 py-3"><option value="">Root category</option>@foreach ($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>@endforeach</select>
            <input name="image_url" value="{{ old('image_url', $category->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Image URL">
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm">Upload category image<input type="file" name="image_file" accept="image/*" class="mt-2 block w-full"></label>
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> Active</label>
            <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $category->is_featured))> Featured</label>
            @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Save Category</button>
        </div>
    </form>
@endsection
