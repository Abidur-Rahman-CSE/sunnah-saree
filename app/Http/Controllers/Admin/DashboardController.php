<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalOrders' => Order::query()->count(),
            'todaysOrders' => Order::query()->whereDate('created_at', today())->count(),
            'pendingOrders' => Order::query()->where('status', 'pending')->count(),
            'totalSales' => Order::query()->whereNotIn('status', ['cancelled'])->sum('total'),
            'lowStockProducts' => Product::query()->whereHas('variants', fn ($query) => $query->whereColumn('quantity', '<=', 'stock_alert_quantity'))->with('variants')->take(8)->get(),
            'recentOrders' => Order::query()->latest()->take(8)->get(),
            'bestSellingProducts' => Product::query()->where('is_best_seller', true)->take(6)->get(),
        ]);
    }
}
