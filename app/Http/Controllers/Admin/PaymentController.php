<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with('order')
            ->when($request->filled('search'), fn ($query) => $query->whereHas('order', fn ($order) => $order->where('order_number', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest();

        return view('admin.payments.index', [
            'payments' => $payments->paginate(20)->withQueryString(),
        ]);
    }
}
