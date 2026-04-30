@extends('layouts.admin', ['heading' => $combo->exists ? 'Edit Combo' : 'Add Combo'])

@section('content')
    @php($selectedQuantities = $combo->items->pluck('quantity', 'product_id'))
    <form action="{{ $combo->exists ? route('admin.combos.update', $combo) : route('admin.combos.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($combo->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <input name="name" value="{{ old('name', $combo->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Combo name">
            <input name="slug" value="{{ old('slug', $combo->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug">
            <input name="image_url" value="{{ old('image_url', $combo->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Image URL">
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload combo image<input type="file" name="image_file" accept="image/*" class="mt-2 block w-full"></label>
            <input name="regular_total_price" value="{{ old('regular_total_price', $combo->regular_total_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Regular total price">
            <input name="discounted_combo_price" value="{{ old('discounted_combo_price', $combo->discounted_combo_price) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Discounted combo price">
            <input name="combo_stock" value="{{ old('combo_stock', $combo->combo_stock ?? 0) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Combo stock">
            <div class="rounded-lg border border-[#ddd4c4] p-4 md:col-span-2">
                <h2 class="font-semibold">Products in Combo</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    @foreach ($products as $product)
                        <label class="grid gap-2 rounded-lg bg-[#faf8f3] p-3 text-sm">
                            <span><input type="checkbox" name="product_ids[]" value="{{ $product->id }}" @checked(in_array($product->id, old('product_ids', $selectedQuantities->keys()->all()), true))> {{ $product->name }}</span>
                            <input name="quantities[{{ $product->id }}]" value="{{ old('quantities.'.$product->id, $selectedQuantities[$product->id] ?? 1) }}" class="rounded-lg border border-[#ddd4c4] px-3 py-2" placeholder="Quantity">
                        </label>
                    @endforeach
                </div>
            </div>
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $combo->is_active ?? true))> Active</label>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Combo</button>
        </div>
    </form>
@endsection
