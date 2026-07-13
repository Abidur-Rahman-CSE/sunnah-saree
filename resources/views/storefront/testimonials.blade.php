@extends('layouts.storefront', ['title' => 'Customer Stories'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="flex flex-col gap-2">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a24a]">Customer Stories</p>
            <h1 class="font-serif text-4xl font-bold text-[#2f241f]">Testimonials</h1>
            <p class="max-w-2xl text-sm leading-6 text-[#6f5a50]">Real customer feedback with images. Facebook-linked testimonials open the original post.</p>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($testimonials as $testimonial)
                @php
                    $testimonialCardClass = 'group overflow-hidden rounded-lg border border-[#ead8ba] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg';
                @endphp
                @if ($testimonial->facebook_post_url)
                    <a href="{{ $testimonial->facebook_post_url }}" target="_blank" rel="noopener" class="{{ $testimonialCardClass }}">
                        @include('storefront.partials.testimonial-card', ['testimonial' => $testimonial])
                    </a>
                @else
                    <article class="{{ $testimonialCardClass }}">
                        @include('storefront.partials.testimonial-card', ['testimonial' => $testimonial])
                    </article>
                @endif
            @empty
                <div class="rounded-lg border border-[#ead8ba] bg-white p-8 text-center text-[#6f5a50] sm:col-span-2 lg:col-span-3">No testimonials available.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $testimonials->links() }}</div>
    </section>
@endsection
