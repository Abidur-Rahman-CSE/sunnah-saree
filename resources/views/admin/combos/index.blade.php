@extends('layouts.admin', ['heading' => 'Combos'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.combos.create')" create-label="Add Combo" search-placeholder="Search combos" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Combo</th><th>Items</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($combos as $combo)
                    <tr class="border-b"><td class="p-3 font-semibold">{{ $combo->name }}</td><td>{{ $combo->items_count }}</td><td>৳{{ number_format((float) $combo->discounted_combo_price) }}</td><td>{{ $combo->combo_stock }}</td><td>{{ $combo->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.combos.edit', $combo) }}">Edit</a></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $combos->links() }}</div>
@endsection
