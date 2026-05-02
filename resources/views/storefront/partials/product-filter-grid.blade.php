@php
    $showCategoryFilter = $showCategoryFilter ?? true;
@endphp

<div class="mt-8 grid gap-6 lg:grid-cols-[280px_1fr]">
    <form class="rounded-lg border border-[#eadcc3] bg-white/90 p-4 shadow-sm backdrop-blur">
        <div class="grid gap-4">
            @if ($showCategoryFilter)
                <x-admin.field label="Category">
                    <select name="category" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-3 py-2" data-category-filter>
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(($selectedCategorySlug ?? request('category')) === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
            @endif

            @foreach (['sharee_type' => 'Sharee Type', 'color' => 'Color', 'occasion' => 'Occasion', 'fabric' => 'Fabric', 'work_type' => 'Work Type'] as $field => $label)
                @php($options = $filters[str($field)->camel()->plural()->toString()] ?? collect())
                @if ($options->isNotEmpty())
                    <x-admin.field :label="$label">
                        <select name="{{ $field }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-3 py-2" data-attribute-filter>
                            <option value="">Any {{ str($label)->lower() }}</option>
                            @foreach ($options as $value)
                                <option value="{{ $value }}" @selected(request($field) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </x-admin.field>
                @endif

                @if ($field === 'color' && ($filters['colorOptions'] ?? collect())->isNotEmpty())
                    <div class="-mt-2 flex flex-wrap gap-2">
                        @foreach ($filters['colorOptions'] as $color)
                            <a href="{{ url()->current().'?'.http_build_query(array_filter([...request()->except('page'), 'color' => $color['name']])) }}" class="h-7 w-7 rounded-full border-2 border-white shadow-[0_0_0_1px_#d8b879]" style="background: {{ $color['code'] }}" title="{{ $color['name'] }}" aria-label="Filter by {{ $color['name'] }}"></a>
                        @endforeach
                    </div>
                @endif
            @endforeach

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <x-admin.field label="Min price"><input name="min_price" value="{{ request('min_price') }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-3 py-2" placeholder="Min"></x-admin.field>
                <x-admin.field label="Max price"><input name="max_price" value="{{ request('max_price') }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-3 py-2" placeholder="Max"></x-admin.field>
            </div>
            <label class="flex gap-2 text-sm"><input type="checkbox" name="availability" value="1" @checked(request('availability'))> In stock</label>
            <label class="flex gap-2 text-sm"><input type="checkbox" name="offer" value="1" @checked(request('offer'))> Offer only</label>
            <x-admin.field label="Sort by">
                <select name="sort" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-3 py-2">
                    <option value="">Latest</option>
                    <option value="price_low" @selected(request('sort') === 'price_low')>Price low to high</option>
                    <option value="price_high" @selected(request('sort') === 'price_high')>Price high to low</option>
                    <option value="popular" @selected(request('sort') === 'popular')>Popular</option>
                </select>
            </x-admin.field>
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white shadow-sm transition hover:bg-[#651845]">Apply Filters</button>
        </div>
    </form>

    <div>
        <div class="grid gap-5 md:grid-cols-3">
            @forelse ($products as $product)
                <x-storefront.product-card :product="$product" />
            @empty
                <div class="rounded-lg border border-[#eadcc3] bg-white p-8 text-center md:col-span-3">No products matched your filters.</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-category-filter]').forEach((select) => {
        select.addEventListener('change', () => {
            select.form?.querySelectorAll('[data-attribute-filter]').forEach((filter) => {
                filter.value = '';
            });
            select.form?.submit();
        });
    });
</script>
