@extends('layouts.storefront', ['title' => 'Combo Deals'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Combo Deals" subtitle="Gift-ready combinations for sharee, ornament, cosmetic, oil, and baby product shoppers." />
        <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3">
            @foreach ($combos as $combo)
                <article class="overflow-hidden rounded-lg border border-[#eadcc3] bg-white shadow-sm">
                    <img src="{{ $combo->image_url }}" alt="{{ $combo->name }}" class="aspect-[4/3] w-full object-cover">
                    <div class="p-3 sm:p-5">
                        <h2 class="font-serif text-lg font-bold leading-tight sm:text-2xl">{{ $combo->name }}</h2>
                        <p class="mt-2 line-clamp-3 text-xs leading-5 text-[#6f5a50] sm:text-sm">{{ $combo->items->map(fn ($item) => $item->product->name.' x '.$item->quantity)->join(' · ') }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-x-2 gap-y-1"><span class="text-sm font-bold text-[#7a1f55] sm:text-base">৳{{ number_format((float) $combo->discounted_combo_price) }}</span><span class="text-xs line-through sm:text-sm">৳{{ number_format((float) $combo->regular_total_price) }}</span></div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
