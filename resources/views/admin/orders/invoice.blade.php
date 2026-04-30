<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-[#f7f5f0] p-6 text-[#2f241f]">
    <div class="no-print mx-auto mb-4 max-w-4xl text-right">
        <button onclick="window.print()" class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Print</button>
    </div>
    <main class="mx-auto max-w-4xl rounded-lg bg-white p-8 shadow-sm">
        <header class="flex flex-col gap-4 border-b border-[#e5ded0] pb-6 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="font-serif text-3xl font-bold text-[#7a1f55]">Sunnah Sharee Ghar</h1>
                <p class="mt-2 text-sm text-[#6f5a50]">Premium sharee and boutique gifts</p>
            </div>
            <div class="text-sm md:text-right">
                <p class="font-bold">Invoice</p>
                <p>{{ $order->order_number }}</p>
                <p>{{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </header>

        <section class="grid gap-6 border-b border-[#e5ded0] py-6 md:grid-cols-2">
            <div>
                <h2 class="font-bold">Customer</h2>
                <div class="mt-2 text-sm text-[#6f5a50]">
                    <p>{{ $order->customer_name }}</p>
                    <p>{{ $order->customer_phone }}</p>
                    <p>{{ $order->customer_email }}</p>
                    <p>{{ $order->shipping_address }}</p>
                </div>
            </div>
            <div class="text-sm md:text-right">
                <p>Status: <strong>{{ str($order->status)->title() }}</strong></p>
                <p>Payment: <strong>{{ str($order->payment_status)->title() }}</strong></p>
                <p>Method: <strong>{{ str($order->payment_method)->upper() }}</strong></p>
            </div>
        </section>

        <table class="mt-6 w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#e5ded0]">
                    <th class="py-3">Product</th>
                    <th>Variant</th>
                    <th>Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b border-[#f1eadf]">
                        <td class="py-3">{{ $item->product_name }}</td>
                        <td>{{ $item->variant_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">৳{{ number_format((float) $item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="ml-auto mt-6 grid max-w-sm gap-2 text-sm">
            <div class="flex justify-between"><span>Subtotal</span><span>৳{{ number_format((float) $order->subtotal) }}</span></div>
            <div class="flex justify-between"><span>Delivery</span><span>৳{{ number_format((float) $order->delivery_charge) }}</span></div>
            <div class="flex justify-between"><span>Discount</span><span>-৳{{ number_format((float) $order->discount_amount) }}</span></div>
            <div class="flex justify-between border-t border-[#e5ded0] pt-3 text-lg font-bold"><span>Total</span><span>৳{{ number_format((float) $order->total) }}</span></div>
        </section>
    </main>
</body>
</html>
