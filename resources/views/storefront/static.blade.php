@extends('layouts.storefront', ['title' => str($page)->replace('-', ' ')->title()])

@section('content')
    @php
        $storePhone = \App\Models\Setting::valueFor('phone', '01985902350');
        $storeWhatsapp = \App\Models\Setting::valueFor('whatsapp', $storePhone);
        $storeWhatsappDigits = preg_replace('/\D+/', '', (string) $storeWhatsapp);

        if (str_starts_with($storeWhatsappDigits, '0')) {
            $storeWhatsappDigits = '88'.$storeWhatsappDigits;
        }

        $policyText = match ($page) {
            'return-policy' => \App\Models\Setting::valueFor('return_policy_text', ''),
            'shipping-policy' => \App\Models\Setting::valueFor('shipping_policy_text', ''),
            'terms-conditions' => \App\Models\Setting::valueFor('terms_and_conditions', ''),
            'privacy-policy' => \App\Models\Setting::valueFor('privacy_policy', ''),
            default => '',
        };
    @endphp

    <section class="mx-auto max-w-3xl px-4 py-12">
        <div class="rounded-lg border border-[#eadcc3] bg-white p-8 shadow-sm">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Customer Care</p>
            <h1 class="mt-2 font-serif text-4xl font-bold">{{ str($page)->replace('-', ' ')->title() }}</h1>

            @if ($policyText !== '')
                <div class="mt-5 whitespace-pre-line leading-8 text-[#6f5a50]">{{ $policyText }}</div>
            @else
                <p class="mt-5 leading-8 text-[#6f5a50]">Sunnah Sharee Ghar keeps policies simple and customer-friendly. Contact our support team for help with product details, delivery timing, returns, privacy questions, or order updates.</p>
            @endif

            @if ($page === 'contact-us')
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <a href="tel:{{ $storePhone }}" class="rounded-lg border border-[#eadcc3] bg-[#fffaf4] p-5 transition hover:border-[#8a155b] hover:shadow-sm">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white text-[#8a155b] shadow-sm">
                            <x-storefront.icon name="phone" class="h-5 w-5" />
                        </span>
                        <span class="mt-4 block text-sm font-bold uppercase tracking-wide text-[#8a155b]">Phone</span>
                        <span class="mt-1 block text-xl font-bold text-[#2f241f]">{{ $storePhone }}</span>
                    </a>
                    <a href="https://wa.me/{{ $storeWhatsappDigits }}" target="_blank" rel="noopener" class="rounded-lg border border-[#eadcc3] bg-[#fffaf4] p-5 transition hover:border-[#8a155b] hover:shadow-sm">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white text-[#8a155b] shadow-sm">
                            <x-storefront.icon name="message-circle" class="h-5 w-5" />
                        </span>
                        <span class="mt-4 block text-sm font-bold uppercase tracking-wide text-[#8a155b]">WhatsApp</span>
                        <span class="mt-1 block text-xl font-bold text-[#2f241f]">{{ $storeWhatsapp }}</span>
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
