<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($search) => $search
                ->where('order_number', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('customer_name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('customer_phone', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest();

        return view('admin.orders.index', [
            'orders' => $orders->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product.images', 'payment', 'user'),
        ]);
    }

    public function invoice(Order $order): View
    {
        return view('admin.orders.invoice', [
            'order' => $order->load('items', 'payment'),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,cancelled,refunded'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update($validated);
        $order->payment?->update(['status' => $validated['payment_status']]);

        return back()->with('status', 'Order updated.');
    }
}
