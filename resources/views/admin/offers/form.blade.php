@extends('layouts.admin', ['heading' => $offer->exists ? 'Edit Offer' : 'Add Offer'])

@section('content')
    <form action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($offer->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <input name="title" value="{{ old('title', $offer->title) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Campaign title">
            <input name="slug" value="{{ old('slug', $offer->slug) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Slug">
            <input name="banner_url" value="{{ old('banner_url', $offer->banner_url) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Banner URL">
            <label class="rounded-lg border border-dashed border-[#cfc3ad] px-4 py-3 text-sm md:col-span-2">Upload offer banner<input type="file" name="banner_file" accept="image/*" class="mt-2 block w-full"></label>
            <textarea name="description" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Description">{{ old('description', $offer->description) }}</textarea>
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $offer->starts_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3">
            <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $offer->ends_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3">
            <select name="product_ids[]" multiple class="min-h-48 rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(in_array($product->id, old('product_ids', $offer->products->pluck('id')->all()), true))>{{ $product->name }}</option>
                @endforeach
            </select>
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $offer->is_active ?? true))> Active</label>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Offer</button>
        </div>
    </form>
@endsection
