<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\View\View;

class ConsumerDashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $totalOrders    = Order::where('consumer_id', $userId)->count();
        $activeOrders   = Order::where('consumer_id', $userId)
            ->whereIn('status', ['confirmed', 'preparing', 'in_transit'])
            ->count();
        $cartCount      = Cart::where('user_id', $userId)->count();
        $totalSpent     = Order::where('consumer_id', $userId)
            ->where('status', 'delivered')
            ->sum('total_amount');

        $recentOrders = Order::with(['items.product'])
            ->where('consumer_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.consumer', compact(
            'totalOrders',
            'activeOrders',
            'cartCount',
            'totalSpent',
            'recentOrders'
        ));
    }
}
