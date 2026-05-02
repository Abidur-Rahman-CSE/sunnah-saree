@extends('layouts.admin', ['heading' => $combo->exists ? 'Edit Combo' : 'Add Combo'])

@section('content')
    @php($selectedQuantities = $combo->items->pluck('quantity', 'product_id'))
    <form action="{{ $combo->exists ? route('admin.combos.update', $combo) : route('admin.combos.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($combo->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Combo name"><input name="name" value="{{ old('name', $combo->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Combo name"></x-admin.field>
            <x-admin.field label="Slug"><input name="slug" value="{{ old('slug', $combo->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug"></x-admin.field>
            <x-admin.field label="Image URL" span><input name="image_url" value="{{ old('image_url', $combo->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Image URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload combo image<input type="file" name="image_file" accept="image/*" data-image-preview="combo-preview" class="mt-2 block w-full"></label>
            <div class="grid gap-2 md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Image preview</p>
                <div id="combo-preview" class="grid grid-cols-4 gap-2 md:grid-cols-6">
                    @if ($combo->image_url)
                        <img src="{{ $combo->image_url }}" alt="{{ $combo->name }}" class="aspect-square rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.field label="Regular total price"><input name="regular_total_price" value="{{ old('regular_total_price', $combo->regular_total_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Regular total price"></x-admin.field>
            <x-admin.field label="Discounted combo price"><input name="discounted_combo_price" value="{{ old('discounted_combo_price', $combo->discounted_combo_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Discounted combo price"></x-admin.field>
            <x-admin.field label="Combo stock"><input name="combo_stock" value="{{ old('combo_stock', $combo->combo_stock ?? 0) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Combo stock"></x-admin.field>
            <x-admin.product-picker
                :products="$products"
                :selected-ids="old('product_ids', $selectedQuantities->keys()->all())"
                :selected-quantities="$selectedQuantities"
                title="Products in Combo"
                with-quantities
            />
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $combo->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Combo</button>
        </div>
    </form>
@endsection
