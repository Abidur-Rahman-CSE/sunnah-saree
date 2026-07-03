@extends('layouts.admin', ['heading' => $product->exists ? 'Edit Product' : 'Add Product'])

@section('content')
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Product name"><input name="name" value="{{ old('name', $product->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Product name" data-product-name></x-admin.field>
            <x-admin.field label="Slug"><input name="slug" value="{{ old('slug', $product->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Auto generated from name" data-auto-slug></x-admin.field>
            <x-admin.field label="Category"><select name="category_id" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></x-admin.field>
            <x-admin.field label="Product type">
                <select name="product_type" class="rounded-lg border border-[#ddd4c4] px-4 py-3" data-product-type>
                    <option value="fashion" @selected(old('product_type', $product->product_type ?: 'fashion') === 'fashion')>Fashion</option>
                    <option value="general" @selected(old('product_type', $product->product_type) === 'general')>General</option>
                </select>
            </x-admin.field>
            <x-admin.field label="Price"><input name="price" value="{{ old('price', $product->price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Price"></x-admin.field>
            <x-admin.field label="Discount price"><input name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Discount price"></x-admin.field>
            <x-admin.field label="SKU"><input name="sku" value="{{ old('sku', $product->sku) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Auto generated from name" data-auto-sku></x-admin.field>
            <x-admin.field label="Badge"><input name="badge" value="{{ old('badge', $product->badge) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Badge"></x-admin.field>
            <x-admin.field label="Description" span><textarea name="description" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Description">{{ old('description', $product->description) }}</textarea></x-admin.field>
            <div class="grid gap-4 rounded-lg border border-[#eadcc3] bg-[#fffaf3] p-4 md:col-span-2">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.field label="Primary image URL"><input name="image_url" value="{{ old('image_url', $product->images->first()?->image_url) }}" class="rounded-lg border border-[#ddd4c4] bg-white px-4 py-3" placeholder="Primary image URL"></x-admin.field>
                    <label class="rounded-lg border border-dashed border-[#cfc3ad] bg-white px-4 py-3 text-sm">
                        Upload primary image
                        <input type="file" name="image_file" accept="image/*" data-image-preview="primary-preview" class="mt-2 block w-full">
                    </label>
                </div>
                <label class="rounded-lg border border-dashed border-[#cfc3ad] bg-white px-4 py-3 text-sm">
                    Upload gallery images
                    <input type="file" name="image_files[]" accept="image/*" multiple data-image-preview="gallery-preview" class="mt-2 block w-full">
                    <span class="mt-1 block text-xs text-[#8d786d]">You can select multiple product images.</span>
                </label>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Primary preview</p>
                        <div id="primary-preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Gallery preview</p>
                        <div id="gallery-preview" class="mt-2 grid grid-cols-4 gap-2"></div>
                    </div>
                </div>
                @if ($product->images->isNotEmpty())
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Current images</p>
                        <div class="mt-2 grid grid-cols-4 gap-2 md:grid-cols-8">
                            @foreach ($product->images as $image)
                                <div class="overflow-hidden rounded-lg border border-[#eadcc3] bg-white">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?: $product->name }}" class="aspect-square w-full object-cover">
                                    <button type="submit" form="delete-product-image-{{ $image->id }}" class="w-full bg-red-50 px-2 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">
                                        Delete
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="grid gap-4 rounded-lg border border-[#eadcc3] bg-[#fffaf3] p-4 md:col-span-2 md:grid-cols-2" data-fashion-fields>
                <div class="md:col-span-2">
                    <h2 class="font-serif text-xl font-bold text-[#2f241f]">Fashion Details</h2>
                    <p class="mt-1 text-sm text-[#8d786d]">Manage dropdown values from Fashion Attributes.</p>
                </div>
                @foreach (['sharee_type' => 'Fashion type', 'fabric' => 'Fabric', 'work_type' => 'Work type', 'color' => 'Color', 'occasion' => 'Occasion'] as $field => $label)
                    <x-admin.field :label="$label">
                        <select name="{{ $field }}" class="rounded-lg border border-[#ddd4c4] bg-white px-4 py-3">
                            <option value="">Select {{ str($label)->lower() }}</option>
                            @foreach ($fashionOptions[$field] ?? [] as $value)
                                <option value="{{ $value }}" @selected(old($field, $product->{$field}) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </x-admin.field>
                @endforeach
                <x-admin.field label="Length"><input name="length" value="{{ old('length', $product->length) }}" class="rounded-lg border border-[#ddd4c4] bg-white px-4 py-3" placeholder="Length"></x-admin.field>
                <x-admin.field label="Care instruction" span><textarea name="care_instruction" rows="3" class="rounded-lg border border-[#ddd4c4] bg-white px-4 py-3" placeholder="Care instruction">{{ old('care_instruction', $product->care_instruction) }}</textarea></x-admin.field>
            </div>
            <x-admin.field label="Collections" span><select name="collection_ids[]" multiple class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach ($collections as $collection)<option value="{{ $collection->id }}" @selected(in_array($collection->id, old('collection_ids', $product->collections->pluck('id')->all()), true))>{{ $collection->name }}</option>@endforeach</select></x-admin.field>
            <x-admin.field label="Variant color"><input name="variant_color" value="{{ old('variant_color', $product->variants->first()?->color) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant color"></x-admin.field>
            <x-admin.field label="Variant SKU"><input name="variant_sku" value="{{ old('variant_sku', $product->variants->first()?->sku) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant SKU"></x-admin.field>
            <x-admin.field label="Variant quantity"><input name="variant_quantity" value="{{ old('variant_quantity', $product->variants->first()?->quantity) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Variant quantity"></x-admin.field>
            <div class="grid gap-2 text-sm">
                <label data-fashion-fields><input type="checkbox" name="blouse_included" value="1" @checked(old('blouse_included', $product->blouse_included))> Blouse included</label>
                <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
                <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
                <label><input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller))> Best seller</label>
                <label><input type="checkbox" name="is_new_arrival" value="1" @checked(old('is_new_arrival', $product->is_new_arrival))> New arrival</label>
            </div>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Product</button>
        </div>
    </form>

    @foreach ($product->images as $image)
        <form id="delete-product-image-{{ $image->id }}" action="{{ route('admin.product-images.destroy', $image) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
