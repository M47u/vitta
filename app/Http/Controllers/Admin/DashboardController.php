<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'approved')->sum('total'),
            'total_products' => Product::count(),
            'low_stock' => ProductVariant::whereColumn('stock', '<=', 'min_stock')->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $topProducts = Product::orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}