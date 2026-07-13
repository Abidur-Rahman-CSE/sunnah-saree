@extends('layouts.admin', ['heading' => $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial'])

@section('content')
    <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Customer name"><input name="customer_name" value="{{ old('customer_name', $testimonial->customer_name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Customer name"></x-admin.field>
            <x-admin.field label="Sort order"><input type="number" min="0" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Sort order"></x-admin.field>
            <x-admin.field label="Image URL" span><input name="image_url" value="{{ old('image_url', $testimonial->image_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Image URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload testimonial image<input type="file" name="image_file" accept="image/*" data-image-preview="testimonial-preview" data-image-preview-shape="wide" class="mt-2 block w-full"><x-admin.image-ratio-guide ratio="4:3" size="1200 x 900 px" usage="Best for testimonial cards." shape="wide" /></label>
            <div class="grid gap-2 md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Image preview</p>
                <div id="testimonial-preview" class="grid grid-cols-4 gap-2 md:grid-cols-6">
                    @if ($testimonial->image_url)
                        <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->customer_name }}" class="aspect-video rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.field label="Facebook post link" span><input name="facebook_post_url" value="{{ old('facebook_post_url', $testimonial->facebook_post_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="https://www.facebook.com/..."></x-admin.field>
            <x-admin.field label="Message" span><textarea name="message" rows="5" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Customer feedback">{{ old('message', $testimonial->message) }}</textarea></x-admin.field>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Testimonial</button>
        </div>
    </form>
@endsection
