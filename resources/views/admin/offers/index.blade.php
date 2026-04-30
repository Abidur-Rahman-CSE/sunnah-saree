@extends('layouts.admin', ['heading' => 'Offers'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.offers.create')" create-label="Add Offer" search-placeholder="Search offers" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Campaign</th><th>Products</th><th>Dates</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($offers as $offer)
                    <tr class="border-b"><td class="p-3 font-semibold">{{ $offer->title }}</td><td>{{ $offer->products_count }}</td><td>{{ $offer->starts_at?->format('M d') ?? 'Open' }} - {{ $offer->ends_at?->format('M d') ?? 'Open' }}</td><td>{{ $offer->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.offers.edit', $offer) }}">Edit</a></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $offers->links() }}</div>
@endsection
