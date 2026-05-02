@extends('layouts.storefront', ['title' => ($pageTitle ?? 'Products').' · Sunnah Sharee Ghar'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title :title="$pageTitle ?? 'Shop Products'" subtitle="Filter by saree type, color, occasion, fabric, work, availability, offer, and price." />
        @include('storefront.partials.product-filter-grid', ['showCategoryFilter' => $showCategoryFilter ?? true])
    </section>
@endsection
