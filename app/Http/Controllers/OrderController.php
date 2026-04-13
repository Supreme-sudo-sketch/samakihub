<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /** POST /orders — create order from cart */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_notes'   => ['nullable', 'string', 'max:500'],
        ]);

        $userId = auth()->id();

        $cartRows = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartRows->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal    = $cartRows->sum(fn($r) => $r->product->price_per_kg * $r->quantity_kg);
        $deliveryFee = 200.00;
        $total       = $subtotal + $deliveryFee;

        $order = Order::create([
            'consumer_id'      => $userId,
            'status'           => 'pending_payment',
            'payment_status'   => 'pending',
            'total_amount'     => $total,
            'delivery_fee'     => $deliveryFee,
            'delivery_address' => $request->input('delivery_address'),
            'delivery_notes'   => $request->input('delivery_notes'),
        ]);

        foreach ($cartRows as $row) {
            $subtotalLine = $row->product->price_per_kg * $row->quantity_kg;
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $row->product_id,
                'quantity_kg' => $row->quantity_kg,
                'unit_price'  => $row->product->price_per_kg,
                'subtotal'    => $subtotalLine,
            ]);

            // Deduct stock
            $row->product->decrement('stock_kg', $row->quantity_kg);
        }

        // Clear cart
        Cart::where('user_id', $userId)->delete();

        return redirect()->route('payment.show', $order->id);
    }

    /** GET /orders — consumer order history */
    public function index(): View
    {
        $orders = Order::with(['items.product'])
            ->where('consumer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('consumer.orders.index', compact('orders'));
    }

    /** GET /orders/{id} — order detail (consumer or delivery agent) */
    public function show(int $id): View
    {
        $order = Order::with([
            'consumer',
            'deliveryAgent',
            'items.product.producer',
            'latestLocation',
        ])->findOrFail($id);

        // Only the consumer or the assigned delivery agent can view
        $user = auth()->user();
        if ($user->role === 'consumer' && $order->consumer_id !== $user->id) {
            abort(403);
        }
        if ($user->role === 'delivery' && $order->delivery_agent_id !== $user->id) {
            abort(403);
        }

        return view('consumer.orders.show', compact('order'));
    }

    /** PATCH /orders/{id}/status — delivery agent advances order status */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        if ($order->delivery_agent_id !== auth()->id()) {
            abort(403);
        }

        $next = Order::NEXT_STATUS[$order->status] ?? null;

        if (! $next) {
            return back()->with('error', 'Order is already at final status.');
        }

        $order->update(['status' => $next]);

        return back()->with('success', 'Order status updated to ' . Order::STATUS_LABELS[$next] . '.');
    }
}
