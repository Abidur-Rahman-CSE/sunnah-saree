@extends('layouts.storefront', ['title' => str($page)->replace('-', ' ')->title()])

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12">
        <div class="rounded-lg border border-[#eadcc3] bg-white p-8 shadow-sm">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Customer Care</p>
            <h1 class="mt-2 font-serif text-4xl font-bold">{{ str($page)->replace('-', ' ')->title() }}</h1>
            <p class="mt-5 leading-8 text-[#6f5a50]">Sunnah Sharee Ghar keeps policies simple and customer-friendly. Contact our support team for help with product details, delivery timing, returns, privacy questions, or order updates.</p>
        </div>
    </section>
@endsection
