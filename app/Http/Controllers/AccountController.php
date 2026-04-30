<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('account.dashboard', [
            'orders' => Order::query()->where('user_id', $request->user()->id)->latest()->take(5)->get(),
        ]);
    }

    public function orders(Request $request): View
    {
        return view('account.orders.index', [
            'orders' => Order::query()->where('user_id', $request->user()->id)->latest()->paginate(10),
        ]);
    }

    public function order(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        return view('account.orders.show', [
            'order' => $order->load('items', 'payment'),
        ]);
    }
}
