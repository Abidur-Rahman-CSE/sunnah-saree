@extends('layouts.storefront', ['title' => 'Combo Deals'])

@section('content')
    <section class="relative mx-auto max-w-7xl px-4 py-8">
        <x-ui.page-pattern />
        <div class="relative overflow-hidden rounded-lg border border-[#ead8ba] bg-gradient-to-br from-white via-[#fffaf4] to-[#fff7ea] p-8 shadow-sm">
            <x-ui.corner-ornament position="top-right" class="hidden md:block" />
            <x-section-title title="Combo Deals" subtitle="Gift-ready combinations for sharee, ornament, cosmetic, oil, and baby product shoppers." />
        </div>
        <div class="relative mt-8 grid gap-5 md:grid-cols-3">
            @foreach ($combos as $combo)
                <article class="overflow-hidden rounded-lg border border-[#ead8ba] bg-white shadow-[0_12px_30px_rgba(89,61,48,0.08)] transition hover:-translate-y-1 hover:shadow-lg">
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
