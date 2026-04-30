@extends('layouts.storefront', ['title' => $product->name])

@section('content')
    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1fr_0.9fr]">
        <div class="grid gap-4 md:grid-cols-[96px_1fr]">
            <div class="hidden gap-3 md:grid">
                @foreach ($product->images as $image)
                    <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" class="aspect-square rounded-lg object-cover">
                @endforeach
            </div>
            <img src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="aspect-[4/5] w-full rounded-lg object-cover shadow-xl">
        </div>
        <div>
            <p class="text-sm font-bold uppercase text-[#c9a24a]">{{ $product->category->name }}</p>
            <h1 class="mt-2 font-serif text-4xl font-bold">{{ $product->name }}</h1>
            <div class="mt-4 flex items-center gap-3">
                <span class="text-3xl font-bold text-[#7a1f55]">৳{{ number_format($product->finalPrice()) }}</span>
                @if ($product->discount_price)
                    <span class="text-lg text-[#8d786d] line-through">৳{{ number_format((float) $product->price) }}</span>
                @endif
            </div>
            <p class="mt-3 text-sm font-semibold text-green-700">{{ $product->variants->sum('quantity') > 0 ? 'In stock' : 'Out of stock' }}</p>

            <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <select name="product_variant_id" class="w-full rounded-lg border border-[#dfcda9] bg-white px-4 py-3">
                    @foreach ($product->variants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->color }} · {{ $variant->quantity }} pcs</option>
                    @endforeach
                </select>
                <input type="number" name="quantity" value="1" min="1" class="w-28 rounded-lg border border-[#dfcda9] px-4 py-3">
                <div class="flex flex-wrap gap-3">
                    <button class="rounded-lg bg-[#7a1f55] px-6 py-3 font-semibold text-white">Add to Cart</button>
                    <button formaction="{{ route('cart.store', $product) }}" class="rounded-lg bg-[#c9a24a] px-6 py-3 font-semibold text-white">Buy Now</button>
                    @auth
                        @php($isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists())
                        <button formaction="{{ $isWishlisted ? route('wishlist.destroy', $product) : route('wishlist.store', $product) }}" formmethod="POST" name="_method" value="{{ $isWishlisted ? 'DELETE' : 'POST' }}" class="rounded-lg border border-[#dfcda9] px-6 py-3 font-semibold text-[#7a1f55]">{{ $isWishlisted ? 'Remove Wishlist' : 'Wishlist' }}</button>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-[#dfcda9] px-6 py-3 font-semibold text-[#7a1f55]">Wishlist</a>
                    @endauth
                    <button type="button" class="rounded-lg border border-[#dfcda9] px-6 py-3 font-semibold text-[#7a1f55]">Share</button>
                </div>
            </form>

            <div class="mt-8 rounded-lg border border-[#eadcc3] bg-white p-5">
                <h2 class="font-serif text-2xl font-bold">Product Details</h2>
                <p class="mt-3 text-[#6f5a50]">{{ $product->description }}</p>
                @if ($product->category->slug === 'sharee')
                    <dl class="mt-5 grid gap-3 text-sm md:grid-cols-2">
                        @foreach (['Sharee type' => $product->sharee_type, 'Fabric' => $product->fabric, 'Work type' => $product->work_type, 'Color' => $product->color, 'Occasion' => $product->occasion, 'Blouse included' => $product->blouse_included ? 'Yes' : 'No', 'Length' => $product->length, 'Care instruction' => $product->care_instruction] as $label => $value)
                            <div class="rounded-lg bg-[#fffaf0] p-3"><dt class="font-semibold">{{ $label }}</dt><dd class="text-[#6f5a50]">{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                @endif
            </div>
            <div class="mt-4 rounded-lg border border-[#eadcc3] bg-white p-5 text-sm text-[#6f5a50]">Delivery: Cash on delivery available. Return: Easy return support for eligible products.</div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Related Products" />
        <div class="mt-6 grid gap-5 md:grid-cols-4">
            @foreach ($relatedProducts->merge($similarColorProducts)->unique('id')->take(4) as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endsection
