@extends('layouts.storefront', ['title' => 'Combo Deals'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Combo Deals" subtitle="Gift-ready combinations for sharee, ornament, cosmetic, oil, and baby product shoppers." />
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            @foreach ($combos as $combo)
                <article class="overflow-hidden rounded-lg border border-[#eadcc3] bg-white shadow-sm">
                    <img src="{{ $combo->image_url }}" alt="{{ $combo->name }}" class="aspect-[4/3] w-full object-cover">
                    <div class="p-5">
                        <h2 class="font-serif text-2xl font-bold">{{ $combo->name }}</h2>
                        <p class="mt-2 text-sm text-[#6f5a50]">{{ $combo->items->map(fn ($item) => $item->product->name.' x '.$item->quantity)->join(' · ') }}</p>
                        <div class="mt-4 flex items-center gap-2"><span class="font-bold text-[#7a1f55]">৳{{ number_format((float) $combo->discounted_combo_price) }}</span><span class="text-sm line-through">৳{{ number_format((float) $combo->regular_total_price) }}</span></div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
