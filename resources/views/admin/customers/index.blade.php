@extends('layouts.admin', ['heading' => 'Customers'])

@section('content')
    <div class="mb-4">
        <form class="grid gap-2 md:grid-cols-[1fr_auto]">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="Search customer, email, phone">
            <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Customer</th><th>Email</th><th>Phone</th><th>Orders</th><th></th></tr></thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr class="border-b"><td class="p-3 font-semibold">{{ $customer->name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone }}</td><td>{{ $customer->orders_count }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.customers.show', $customer) }}">View</a></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $customers->links() }}</div>
@endsection
