@extends('layouts.admin', ['heading' => $order->order_number])

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="rounded-lg border border-[#7a1f55] px-4 py-2 font-semibold text-[#7a1f55]">Print Invoice</a>
    </div>
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="grid gap-6">
            <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
                <h2 class="font-bold">Customer Info</h2>
                <div class="mt-4 grid gap-4 text-sm md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Name</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Phone</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Email</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_email ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Account</p>
                        <p class="mt-1 font-semibold">{{ $order->user?->email ?: 'Guest checkout' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Shipping Address</p>
                        <p class="mt-1 font-semibold">{{ collect([$order->shipping_area, $order->shipping_district, $order->shipping_division])->filter()->join(', ') }}</p>
                        <p class="mt-1 font-semibold">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
                <h2 class="font-bold">Items</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-xs font-bold uppercase tracking-wide text-[#8d786d]">
                                <th class="py-3">Product</th>
                                <th>Variant</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b last:border-0">
                                    <td class="py-3">
                                        @if ($item->product)
                                            <button
                                                type="button"
                                                class="flex min-w-64 items-center gap-3 rounded-lg text-left transition hover:bg-[#fffaf4]"
                                                data-product-details-trigger
                                                data-product-name="{{ $item->product->name }}"
                                                data-product-id="{{ $item->product->id }}"
                                                data-product-sku="{{ $item->product->sku }}"
                                                data-product-category="{{ $item->product->category?->name ?? 'Uncategorized' }}"
                                                data-product-price="৳{{ number_format($item->product->finalPrice()) }}"
                                                data-product-stock="{{ $item->product->quantity }}"
                                                data-product-status="{{ $item->product->is_active ? 'Active' : 'Inactive' }}"
                                                data-product-type="{{ $item->product->product_type }}"
                                                data-product-color="{{ $item->product->color ?: 'N/A' }}"
                                                data-product-image="{{ $item->product->primaryImage() }}"
                                                data-product-description="{{ str($item->product->description)->limit(180) }}"
                                                data-product-url="{{ route('products.show', $item->product) }}"
                                                data-product-edit-url="{{ route('admin.products.edit', $item->product) }}"
                                            >
                                                <img src="{{ $item->product->primaryImage() }}" alt="{{ $item->product_name }}" class="h-14 w-14 rounded-lg border border-[#eadcc3] object-cover">
                                                <span>
                                                    <span class="block font-semibold text-[#2f1f1a]">{{ $item->product_name }}</span>
                                                    <span class="mt-1 block text-xs font-semibold text-[#8d786d]">Product ID: {{ $item->product_id }}</span>
                                                    <span class="mt-1 block text-xs font-semibold text-[#7a1f55]">Click for details</span>
                                                </span>
                                            </button>
                                        @else
                                            <div class="flex min-w-64 items-center gap-3">
                                                <span class="grid h-14 w-14 place-items-center rounded-lg border border-dashed border-[#d8c7a8] bg-[#fffaf4] text-xs font-bold text-[#8d786d]">No image</span>
                                                <div>
                                                    <p class="font-semibold text-[#2f1f1a]">{{ $item->product_name }}</p>
                                                    <p class="mt-1 text-xs font-semibold text-[#8d786d]">Product ID: {{ $item->product_id ?? 'Deleted' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->variant_name ?: 'Default' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="font-semibold">৳{{ number_format((float) $item->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="h-fit rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
            @csrf @method('PATCH')
            <h2 class="font-bold">Manage Order</h2>
            <div class="mt-4 grid gap-4">
                <x-admin.field label="Order status"><select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ str($status)->title() }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Payment status"><select name="payment_status" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach (['pending','paid','failed','cancelled','refunded'] as $status)<option value="{{ $status }}" @selected($order->payment_status === $status)>{{ str($status)->title() }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Admin note"><textarea name="admin_note" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Admin note">{{ $order->admin_note }}</textarea></x-admin.field>
                <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Update Order</button>
            </div>
        </form>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4" data-product-details-modal>
        <div class="w-full max-w-2xl rounded-lg bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-[#eadcc3] px-5 py-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Product details</p>
                    <h2 class="font-serif text-2xl font-bold text-[#2f1f1a]" data-modal-product-name></h2>
                </div>
                <button type="button" class="grid h-10 w-10 place-items-center rounded-lg border border-red-200 text-red-700" data-product-details-close aria-label="Close product details">x</button>
            </div>
            <div class="grid gap-5 p-5 md:grid-cols-[180px_1fr]">
                <img src="" alt="" class="aspect-square w-full rounded-lg border border-[#eadcc3] object-cover" data-modal-product-image>
                <div class="grid gap-4 text-sm">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-[#fffaf4] p-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Product ID</p>
                            <p class="mt-1 font-semibold" data-modal-product-id></p>
                        </div>
                        <div class="rounded-lg bg-[#fffaf4] p-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">SKU</p>
                            <p class="mt-1 font-semibold" data-modal-product-sku></p>
                        </div>
                        <div class="rounded-lg bg-[#fffaf4] p-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Price</p>
                            <p class="mt-1 font-semibold" data-modal-product-price></p>
                        </div>
                        <div class="rounded-lg bg-[#fffaf4] p-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Stock</p>
                            <p class="mt-1 font-semibold" data-modal-product-stock></p>
                        </div>
                    </div>
                    <div class="grid gap-2 text-[#4f3d36]">
                        <p><span class="font-bold">Category:</span> <span data-modal-product-category></span></p>
                        <p><span class="font-bold">Status:</span> <span data-modal-product-status></span></p>
                        <p><span class="font-bold">Type:</span> <span data-modal-product-type></span></p>
                        <p><span class="font-bold">Color:</span> <span data-modal-product-color></span></p>
                        <p class="leading-6 text-[#6f5a50]" data-modal-product-description></p>
                    </div>
                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="#" target="_blank" rel="noopener" class="rounded-lg border border-[#7a1f55] px-4 py-2 font-semibold text-[#7a1f55]" data-modal-product-edit-url>Go to Edit</a>
                        <a href="#" target="_blank" rel="noopener" class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white" data-modal-product-url>Go to product</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-product-details-modal]').forEach((modal) => {
            const close = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };
            const show = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };
            const setText = (selector, value) => {
                const element = modal.querySelector(selector);

                if (element) {
                    element.textContent = value || 'N/A';
                }
            };

            document.querySelectorAll('[data-product-details-trigger]').forEach((button) => {
                button.addEventListener('click', () => {
                    const image = modal.querySelector('[data-modal-product-image]');
                    const url = modal.querySelector('[data-modal-product-url]');
                    const editUrl = modal.querySelector('[data-modal-product-edit-url]');

                    setText('[data-modal-product-name]', button.dataset.productName);
                    setText('[data-modal-product-id]', `#${button.dataset.productId}`);
                    setText('[data-modal-product-sku]', button.dataset.productSku);
                    setText('[data-modal-product-category]', button.dataset.productCategory);
                    setText('[data-modal-product-price]', button.dataset.productPrice);
                    setText('[data-modal-product-stock]', button.dataset.productStock);
                    setText('[data-modal-product-status]', button.dataset.productStatus);
                    setText('[data-modal-product-type]', button.dataset.productType);
                    setText('[data-modal-product-color]', button.dataset.productColor);
                    setText('[data-modal-product-description]', button.dataset.productDescription);

                    if (image) {
                        image.src = button.dataset.productImage || '';
                        image.alt = button.dataset.productName || '';
                    }

                    if (url) {
                        url.href = button.dataset.productUrl || '#';
                    }

                    if (editUrl) {
                        editUrl.href = button.dataset.productEditUrl || '#';
                    }

                    show();
                });
            });

            modal.querySelectorAll('[data-product-details-close]').forEach((button) => {
                button.addEventListener('click', close);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    close();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    close();
                }
            });
        });
    </script>
@endsection
