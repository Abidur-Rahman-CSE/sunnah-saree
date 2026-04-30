@props(['product'])

<article class="group overflow-hidden rounded-lg border border-[#ead8ba] bg-white shadow-[0_12px_30px_rgba(89,61,48,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_40px_rgba(122,31,85,0.14)]">
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-[#f8efe1]">
        <img src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <span class="absolute left-3 top-3 rounded-full bg-[#8a155b] px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-white">{{ $product->badge ?? 'Boutique' }}</span>
    </a>
    <div class="space-y-3 p-3">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wide text-[#b78a34]">{{ $product->category?->name }}</p>
                <h3 class="mt-1 font-serif text-base font-bold leading-tight text-[#2f241f]">{{ $product->name }}</h3>
            </div>
            @auth
                <form action="{{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? route('wishlist.destroy', $product) : route('wishlist.store', $product) }}" method="POST">
                    @csrf
                    @if (auth()->user()->wishlists()->where('product_id', $product->id)->exists())
                        @method('DELETE')
                    @endif
                    <button class="rounded-full border border-[#dfcda9] bg-[#fffaf4] px-3 py-1 text-sm text-[#8a155b]">{{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? '♥' : '♡' }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-[#dfcda9] bg-[#fffaf4] px-3 py-1 text-sm text-[#8a155b]">♡</a>
            @endauth
        </div>
        <div class="flex items-center gap-2">
            <span class="font-bold text-[#8a155b]">৳{{ number_format($product->finalPrice()) }}</span>
            @if ($product->discount_price)
                <span class="text-sm text-[#8d786d] line-through">৳{{ number_format((float) $product->price) }}</span>
            @endif
        </div>
        <div class="grid grid-cols-[1fr_auto] gap-2 border-t border-[#f0e5d1] pt-3">
            <a href="{{ route('products.show', $product) }}" class="rounded-lg px-3 py-2 text-center text-xs font-bold text-[#8a155b]">Quick View</a>
            <form action="{{ route('cart.store', $product) }}" method="POST">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button class="rounded-lg bg-[#8a155b] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#6f1047]">Add to Cart</button>
            </form>
        </div>
    </div>
</article>
