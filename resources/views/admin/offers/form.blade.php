@extends('layouts.admin', ['heading' => $offer->exists ? 'Edit Offer' : 'Add Offer'])

@section('content')
    <form action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($offer->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Campaign title"><input name="title" value="{{ old('title', $offer->title) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Campaign title"></x-admin.field>
            <x-admin.field label="Slug"><input name="slug" value="{{ old('slug', $offer->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug"></x-admin.field>
            <x-admin.field label="Banner URL" span><input name="banner_url" value="{{ old('banner_url', $offer->banner_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Banner URL"></x-admin.field>
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload offer banner<input type="file" name="banner_file" accept="image/*" data-image-preview="offer-preview" class="mt-2 block w-full"></label>
            <div class="grid gap-2 md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8a155b]">Banner preview</p>
                <div id="offer-preview" class="grid grid-cols-4 gap-2 md:grid-cols-6">
                    @if ($offer->banner_url)
                        <img src="{{ $offer->banner_url }}" alt="{{ $offer->title }}" class="aspect-square rounded-lg border border-[#eadcc3] object-cover">
                    @endif
                </div>
            </div>
            <x-admin.field label="Description" span><textarea name="description" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Description">{{ old('description', $offer->description) }}</textarea></x-admin.field>
            <x-admin.field label="Starts at"><input type="datetime-local" name="starts_at" value="{{ old('starts_at', $offer->starts_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3"></x-admin.field>
            <x-admin.field label="Ends at"><input type="datetime-local" name="ends_at" value="{{ old('ends_at', $offer->ends_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3"></x-admin.field>
            <x-admin.product-picker
                :products="$products"
                :selected-ids="old('product_ids', $offer->products->pluck('id')->all())"
                title="Offer Products"
            />
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $offer->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Offer</button>
        </div>
    </form>
@endsection
