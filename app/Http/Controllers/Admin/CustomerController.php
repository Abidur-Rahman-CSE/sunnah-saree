<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($search) => $search
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('email', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('phone', 'like', '%'.$request->string('search')->toString().'%')))
            ->latest();

        return view('admin.customers.index', [
            'customers' => $customers->paginate(20)->withQueryString(),
        ]);
    }

    public function show(User $customer): View
    {
        abort_unless($customer->role === 'customer', 404);

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders' => Order::query()->where('user_id', $customer->id)->latest()->paginate(10),
        ]);
    }
}
