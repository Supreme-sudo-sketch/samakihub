<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /** GET /producer/payouts */
    public function index(): View
    {
        $userId = auth()->id();

        $myProductIds = Product::where('user_id', $userId)->pluck('id');

        $totalEarned = OrderItem::whereIn('product_id', $myProductIds)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->sum('subtotal');

        $totalWithdrawn = Withdrawal::where('producer_id', $userId)
            ->whereIn('status', ['approved', 'pending'])
            ->sum('amount');

        $availableBalance = max(0, $totalEarned - $totalWithdrawn);

        $withdrawals = Withdrawal::where('producer_id', $userId)
            ->latest()
            ->paginate(10);

        return view('producer.payouts.index', compact(
            'totalEarned',
            'totalWithdrawn',
            'availableBalance',
            'withdrawals'
        ));
    }

    /** POST /producer/payouts */
    public function store(Request $request): RedirectResponse
    {
        $userId = auth()->id();

        $myProductIds = Product::where('user_id', $userId)->pluck('id');

        $totalEarned = OrderItem::whereIn('product_id', $myProductIds)
            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
            ->sum('subtotal');

        $totalWithdrawn = Withdrawal::where('producer_id', $userId)
            ->whereIn('status', ['approved', 'pending'])
            ->sum('amount');

        $availableBalance = max(0, $totalEarned - $totalWithdrawn);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:100', "max:{$availableBalance}"],
            'phone'  => ['required', 'string', 'max:20'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        Withdrawal::create([
            'producer_id' => $userId,
            'amount'      => $request->amount,
            'phone'       => $request->phone,
            'notes'       => $request->notes,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Withdrawal request submitted! We will process it within 24 hours.');
    }
}
