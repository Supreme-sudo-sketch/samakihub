<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLocation;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    /** GET /delivery/dashboard */
    public function dashboard(): View
    {
        $agentId = auth()->id();

        // Orders this agent is actively handling (not delivered/cancelled)
        $myOrders = Order::with(['consumer', 'items.product'])
            ->where('delivery_agent_id', $agentId)
            ->whereIn('status', ['confirmed', 'preparing', 'in_transit'])
            ->latest()
            ->get();

        // All unclaimed orders (no delivery agent yet) - any status except delivered/cancelled
        $available = Order::with(['items.product', 'consumer'])
            ->whereNull('delivery_agent_id')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->get();

        // Delivered today
        $completed = Order::with(['items'])
            ->where('delivery_agent_id', $agentId)
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->latest()
            ->get();

        return view('delivery.dashboard', compact('myOrders', 'available', 'completed'));
    }

    /** POST /delivery/orders/{orderId}/assign */
    public function assignSelf(int $orderId): RedirectResponse
    {
        $order = Order::findOrFail($orderId);

        if ($order->delivery_agent_id) {
            return back()->with('error', 'This order has already been claimed.');
        }

        $order->update([
            'delivery_agent_id' => auth()->id(),
            'status'            => 'preparing',
        ]);

        return back()->with('success', "Order #{$order->id} claimed! Start preparing for delivery.");
    }

    /** POST /delivery/orders/{orderId}/location */
    public function updateLocation(Request $request, int $orderId): JsonResponse
    {
        $request->validate([
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'status_update' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::findOrFail($orderId);

        if ($order->delivery_agent_id !== auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized.'], 403);
        }

        DeliveryLocation::create([
            'order_id'          => $order->id,
            'delivery_agent_id' => auth()->id(),
            'latitude'          => $request->input('latitude'),
            'longitude'         => $request->input('longitude'),
            'status_update'     => $request->input('status_update'),
        ]);

        return response()->json(['success' => true]);
    }
}
