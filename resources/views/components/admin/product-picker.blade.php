@props([
    'products',
    'selectedIds' => [],
    'selectedQuantities' => collect(),
    'withQuantities' => false,
    'title' => 'Select Products',
])

@php
    $pickerId = 'product-picker-'.uniqid();
    $selectedIds = collect($selectedIds)->map(fn ($id) => (int) $id)->all();
    $categories = $products->pluck('category.name')->filter()->unique()->sort()->values();
@endphp

<div id="{{ $pickerId }}" class="rounded-lg border border-[#ddd4c4] bg-[#fffaf3] p-4 md:col-span-2" data-product-picker>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-semibold">{{ $title }}</h2>
            <p class="mt-1 text-sm text-[#8d786d]"><span data-selected-count>{{ count($selectedIds) }}</span> products selected</p>
        </div>
        <button type="button" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white" data-picker-open>Choose Products</button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2" data-selected-list>
        @foreach ($products->whereIn('id', $selectedIds) as $product)
            <span class="rounded-full border border-[#eadcc3] bg-white px-3 py-1 text-xs font-semibold text-[#7a1f55]" data-selected-chip="{{ $product->id }}">{{ $product->name }}</span>
        @endforeach
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/45 p-4" data-picker-modal>
        <div class="max-h-[90vh] w-full max-w-4xl overflow-hidden rounded-lg bg-white shadow-2xl">
            <div class="border-b border-[#eadcc3] bg-[#faf8f3] p-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-[#2f241f]">{{ $title }}</h3>
                        <p class="text-sm text-[#8d786d]">Search, filter, then tick products to include.</p>
                    </div>
                    <button type="button" class="rounded-full border border-[#eadcc3] px-3 py-1 text-lg leading-none text-[#7a1f55]" data-picker-close aria-label="Close product picker">×</button>
                </div>
                <div class="mt-4 grid gap-3 md:grid-cols-[1fr_220px]">
                    <input type="search" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Search product name, SKU, color..." data-picker-search>
                    <select class="rounded-lg border border-[#ddd4c4] px-4 py-3" data-picker-category>
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ \Illuminate\Support\Str::lower($category) }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="max-h-[58vh] overflow-y-auto p-4">
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($products as $product)
                        @php($categoryName = $product->category?->name ?? 'Uncategorized')
                        <label class="grid cursor-pointer grid-cols-[64px_1fr_auto] items-center gap-3 rounded-lg border border-[#eadcc3] bg-white p-2 transition hover:border-[#8a155b]" data-picker-row data-search="{{ \Illuminate\Support\Str::lower($product->name.' '.$product->sku.' '.$product->color.' '.$categoryName) }}" data-category="{{ \Illuminate\Support\Str::lower($categoryName) }}">
                            <img src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                            <span class="min-w-0">
                                <span class="block truncate font-semibold text-[#2f241f]">{{ $product->name }}</span>
                                <span class="mt-1 block text-xs text-[#8d786d]">{{ $categoryName }} · {{ $product->sku }}</span>
                                @if ($withQuantities)
                                    <span class="mt-2 flex items-center gap-2 text-xs text-[#6f5a50]">
                                        Qty
                                        <input name="quantities[{{ $product->id }}]" value="{{ old('quantities.'.$product->id, $selectedQuantities[$product->id] ?? 1) }}" min="1" class="w-20 rounded-md border border-[#ddd4c4] px-2 py-1" data-picker-quantity>
                                    </span>
                                @endif
                            </span>
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" data-picker-checkbox data-product-name="{{ $product->name }}" class="h-5 w-5 accent-[#7a1f55]" @checked(in_array($product->id, old('product_ids', $selectedIds), false))>
                        </label>
                    @endforeach
                </div>
                <p class="hidden rounded-lg border border-[#eadcc3] bg-[#fffaf3] p-4 text-center text-sm text-[#8d786d]" data-picker-empty>No products match your search.</p>
            </div>

            <div class="flex items-center justify-between border-t border-[#eadcc3] bg-[#faf8f3] p-4">
                <button type="button" class="text-sm font-semibold text-[#7a1f55]" data-picker-clear>Clear selection</button>
                <button type="button" class="rounded-lg bg-[#7a1f55] px-5 py-2 text-sm font-semibold text-white" data-picker-close>Done</button>
            </div>
        </div>
    </div>
</div>
