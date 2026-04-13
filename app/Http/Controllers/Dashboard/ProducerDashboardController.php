<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Question;
use Illuminate\View\View;

class ProducerDashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $totalProducts  = Product::where('user_id', $userId)->count();
        $activeProducts = Product::where('user_id', $userId)->where('is_available', true)->count();

        // Orders that contain at least one of this producer's products
        $myProductIds = Product::where('user_id', $userId)->pluck('id');

        $totalRevenue = \App\Models\OrderItem::whereIn('product_id', $myProductIds)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->sum('subtotal');

        $pendingQuestions = Question::where('producer_id', $userId)
            ->whereNull('answer')
            ->count();

        // First product that has an unanswered question (for the "Answer Questions" link)
        $firstUnansweredProductId = Question::where('producer_id', $userId)
            ->whereNull('answer')
            ->value('product_id');

        $recentOrders = \App\Models\OrderItem::with(['order.consumer', 'product'])
            ->whereIn('product_id', $myProductIds)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.producer', compact(
            'totalProducts',
            'activeProducts',
            'totalRevenue',
            'pendingQuestions',
            'firstUnansweredProductId',
            'recentOrders'
        ));
    }
}
