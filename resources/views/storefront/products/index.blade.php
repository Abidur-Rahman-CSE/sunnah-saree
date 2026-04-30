@extends('layouts.storefront', ['title' => ($pageTitle ?? 'Products').' · Sunnah Sharee Ghar'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title :title="$pageTitle ?? 'Shop Products'" subtitle="Filter by saree type, color, occasion, fabric, work, availability, offer, and price." />
        <div class="mt-8 grid gap-6 lg:grid-cols-[280px_1fr]">
            <form class="rounded-lg border border-[#eadcc3] bg-white p-4 shadow-sm">
                <div class="grid gap-4">
                    <select name="category" class="rounded-lg border border-[#dfcda9] px-3 py-2">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @foreach (['sharee_type' => 'Sharee Type', 'color' => 'Color', 'occasion' => 'Occasion', 'fabric' => 'Fabric', 'work_type' => 'Work Type'] as $field => $label)
                        <select name="{{ $field }}" class="rounded-lg border border-[#dfcda9] px-3 py-2">
                            <option value="">{{ $label }}</option>
                            @foreach ($filters[str($field)->camel()->plural()->toString()] ?? [] as $value)
                                <option value="{{ $value }}" @selected(request($field) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    @endforeach
                    <div class="grid grid-cols-2 gap-2">
                        <input name="min_price" value="{{ request('min_price') }}" class="rounded-lg border border-[#dfcda9] px-3 py-2" placeholder="Min">
                        <input name="max_price" value="{{ request('max_price') }}" class="rounded-lg border border-[#dfcda9] px-3 py-2" placeholder="Max">
                    </div>
                    <label class="flex gap-2 text-sm"><input type="checkbox" name="availability" value="1" @checked(request('availability'))> In stock</label>
                    <label class="flex gap-2 text-sm"><input type="checkbox" name="offer" value="1" @checked(request('offer'))> Offer only</label>
                    <select name="sort" class="rounded-lg border border-[#dfcda9] px-3 py-2">
                        <option value="">Latest</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>Price low to high</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>Price high to low</option>
                        <option value="popular" @selected(request('sort') === 'popular')>Popular</option>
                    </select>
                    <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Apply Filters</button>
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
    </section>
@endsection
