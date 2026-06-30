@extends('layouts.storefront', ['title' => $product->name])

@section('content')
    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-6 sm:gap-8 sm:py-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)]">
        <div class="min-w-0">
            <div class="overflow-hidden rounded-lg border border-[#e5cf9b] bg-white p-2 shadow-xl shadow-[#7a1f55]/10">
                <img id="product-main-image" src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="aspect-[4/5] w-full rounded-md object-cover">
            </div>
            @if ($product->images->count() > 1)
                <div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-5">
                    @foreach ($product->images as $image)
                        <button type="button" class="rounded-lg border border-[#eadcc3] bg-white p-1 shadow-sm transition hover:border-[#8a155b]" data-gallery-image="{{ $image->image_url }}" aria-label="View {{ $image->alt_text ?: $product->name }} image">
                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?: $product->name }}" class="aspect-square w-full rounded-md object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">{{ $product->category->name }}</p>
            <h1 class="mt-2 font-serif text-3xl font-bold leading-tight sm:text-4xl">{{ $product->name }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <span class="text-2xl font-bold text-[#7a1f55] sm:text-3xl">৳{{ number_format($product->finalPrice()) }}</span>
                @if ($product->discount_price)
                    <span class="text-lg text-[#8d786d] line-through">৳{{ number_format((float) $product->price) }}</span>
                @endif
            </div>
            <p class="mt-3 text-sm font-semibold text-green-700">{{ $product->variants->sum('quantity') > 0 ? 'In stock' : 'Out of stock' }}</p>

            <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <x-admin.field label="Variant">
                    <select name="product_variant_id" class="w-full min-w-0 rounded-lg border border-[#dfcda9] bg-white px-4 py-3">
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->color }} · {{ $variant->quantity }} pcs</option>
                        @endforeach
                    </select>
                </x-admin.field>
                <x-admin.field label="Quantity" class="max-w-32"><input type="number" name="quantity" value="1" min="1" class="w-full rounded-lg border border-[#dfcda9] px-4 py-3"></x-admin.field>
                <div class="grid grid-cols-2 gap-3 sm:flex sm:flex-wrap">
                    <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white sm:px-6">Add to Cart</button>
                    <button formaction="{{ route('cart.store', $product) }}" class="rounded-lg bg-[#c9a24a] px-4 py-3 font-semibold text-white sm:px-6">Buy Now</button>
                    @auth
                        @php($isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists())
                        <button formaction="{{ $isWishlisted ? route('wishlist.destroy', $product) : route('wishlist.store', $product) }}" formmethod="POST" name="_method" value="{{ $isWishlisted ? 'DELETE' : 'POST' }}" class="w-full rounded-lg border border-[#dfcda9] px-4 py-3 font-semibold text-[#7a1f55] sm:w-auto sm:px-6">{{ $isWishlisted ? 'Remove Wishlist' : 'Wishlist' }}</button>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3 text-center font-semibold text-[#7a1f55] sm:px-6">Wishlist</a>
                    @endauth
                    <button type="button" class="rounded-lg border border-[#dfcda9] px-4 py-3 font-semibold text-[#7a1f55] sm:px-6">Share</button>
                </div>
            </form>

            <div class="mt-8 rounded-lg border border-[#eadcc3] bg-white p-5">
                <h2 class="font-serif text-2xl font-bold">Product Details</h2>
                <p class="mt-3 text-[#6f5a50]">{{ $product->description }}</p>
                @if ($product->product_type !== 'general')
                    <dl class="mt-5 grid gap-3 text-sm md:grid-cols-2">
                        @foreach (['Sharee type' => $product->sharee_type, 'Fabric' => $product->fabric, 'Work type' => $product->work_type, 'Color' => $product->color, 'Occasion' => $product->occasion, 'Blouse included' => $product->blouse_included ? 'Yes' : 'No', 'Length' => $product->length, 'Care instruction' => $product->care_instruction] as $label => $value)
                            <div class="rounded-lg bg-[#fffaf0] p-3">
                                <dt class="font-semibold">{{ $label }}</dt>
                                <dd class="mt-1 flex items-center gap-2 text-[#6f5a50]">
                                    @if ($label === 'Color' && $colorCode)
                                        <span class="h-5 w-5 rounded-full border-2 border-white shadow-[0_0_0_1px_#d8b879]" style="background: {{ $colorCode }}"></span>
                                    @endif
                                    {{ $value }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>
            <div class="mt-4 rounded-lg border border-[#eadcc3] bg-white p-5 text-sm text-[#6f5a50]">Delivery: Cash on delivery available. Return: Easy return support for eligible products.</div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Related Products" />
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($relatedProducts->merge($similarColorProducts)->unique('id')->take(4) as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>
    <script>
        document.querySelectorAll('[data-gallery-image]').forEach((button) => {
            button.addEventListener('click', () => {
                const mainImage = document.getElementById('product-main-image');

                if (mainImage) {
                    mainImage.src = button.dataset.galleryImage;
                }
            });
        });
    </script>
@endsection
