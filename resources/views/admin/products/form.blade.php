@extends('layouts.admin', ['heading' => $product->exists ? 'Edit Product' : 'Add Product'])

@section('content')
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <input name="name" value="{{ old('name', $product->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Product name">
            <input name="slug" value="{{ old('slug', $product->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug">
            <select name="category_id" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>
            <input name="product_type" value="{{ old('product_type', $product->product_type ?? 'Sharee') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Product type">
            <input name="price" value="{{ old('price', $product->price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Price">
            <input name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Discount price">
            <input name="sku" value="{{ old('sku', $product->sku) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="SKU">
            <input name="badge" value="{{ old('badge', $product->badge) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Badge">
            <textarea name="description" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Description">{{ old('description', $product->description) }}</textarea>
            <input name="image_url" value="{{ old('image_url', $product->images->first()?->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Primary image URL">
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload product image<input type="file" name="image_file" accept="image/*" class="mt-2 block w-full"></label>
            <input name="sharee_type" value="{{ old('sharee_type', $product->sharee_type) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Sharee type">
            <input name="fabric" value="{{ old('fabric', $product->fabric) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Fabric">
            <input name="work_type" value="{{ old('work_type', $product->work_type) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Work type">
            <input name="color" value="{{ old('color', $product->color) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Color">
            <input name="occasion" value="{{ old('occasion', $product->occasion) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Occasion">
            <input name="length" value="{{ old('length', $product->length) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Length">
            <textarea name="care_instruction" rows="3" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Care instruction">{{ old('care_instruction', $product->care_instruction) }}</textarea>
            <select name="collection_ids[]" multiple class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2">@foreach ($collections as $collection)<option value="{{ $collection->id }}" @selected(in_array($collection->id, old('collection_ids', $product->collections->pluck('id')->all()), true))>{{ $collection->name }}</option>@endforeach</select>
            <input name="variant_color" value="{{ old('variant_color', $product->variants->first()?->color) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant color">
            <input name="variant_sku" value="{{ old('variant_sku', $product->variants->first()?->sku) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant SKU">
            <input name="variant_quantity" value="{{ old('variant_quantity', $product->variants->first()?->quantity) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant quantity">
            <div class="grid gap-2 text-sm">
                <label><input type="checkbox" name="blouse_included" value="1" @checked(old('blouse_included', $product->blouse_included))> Blouse included</label>
                <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
                <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
                <label><input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller))> Best seller</label>
                <label><input type="checkbox" name="is_new_arrival" value="1" @checked(old('is_new_arrival', $product->is_new_arrival))> New arrival</label>
            </div>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Product</button>
        </div>
    </form>
@endsection
