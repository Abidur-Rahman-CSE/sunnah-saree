@extends('layouts.admin', ['heading' => 'Products'])

@section('content')
    <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <form class="grid gap-2 md:grid-cols-[1fr_220px_160px_auto]">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="Search name, SKU, color">
            <select name="category_id" class="rounded-lg border border-[#ddd4c4] px-4 py-2"><option value="">All categories</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select>
            <select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-2"><option value="">Any status</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select>
            <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-center font-semibold text-white">Add Product</a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach ($products as $product)
                <tr class="border-b"><td class="p-3 font-semibold">{{ $product->name }}</td><td>{{ $product->category->name }}</td><td>৳{{ number_format($product->finalPrice()) }}</td><td>{{ $product->variants->sum('quantity') }}</td><td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.products.edit', $product) }}">Edit</a></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $products->links() }}</div>
@endsection
