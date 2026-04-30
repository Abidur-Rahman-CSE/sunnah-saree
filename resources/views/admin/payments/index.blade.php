@extends('layouts.admin', ['heading' => 'Payments'])

@section('content')
    <div class="mb-4">
        <form class="grid gap-2 md:grid-cols-[1fr_180px_auto]">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="Search order number">
            <select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-2"><option value="">Any status</option>@foreach (['pending','paid','failed','cancelled','refunded'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->title() }}</option>@endforeach</select>
            <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Order</th><th>Method</th><th>Status</th><th>Amount</th><th>Transaction</th></tr></thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="border-b"><td class="p-3"><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.orders.show', $payment->order) }}">{{ $payment->order->order_number }}</a></td><td>{{ str($payment->method)->upper() }}</td><td>{{ str($payment->status)->title() }}</td><td>৳{{ number_format((float) $payment->amount) }}</td><td>{{ $payment->transaction_id ?? 'Pending' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $payments->links() }}</div>
@endsection
