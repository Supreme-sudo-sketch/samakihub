<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PesaPalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private PesaPalService $pesapal) {}

    /**
     * GET /orders/{id}/pay — show the payment page.
     */
    public function show(int $id): View|RedirectResponse
    {
        $order = Order::with('items.product')->findOrFail($id);

        $this->authorizeConsumer($order);

        // Already paid — go straight to order detail
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Your order is already paid.');
        }

        return view('consumer.payment.pay', compact('order'));
    }

    /**
     * POST /orders/{id}/pay — initiate PesaPal payment.
     */
    public function initiate(Request $request, int $id): JsonResponse
    {
        try {
            $order = Order::with('consumer')->findOrFail($id);
            $this->authorizeConsumer($order);

            if ($order->payment_status === 'paid') {
                return response()->json(['success' => true, 'paid' => true]);
            }

            $request->validate([
                'phone' => ['required', 'string', 'regex:/^(?:254|\+254|0)7\d{8}$/'],
            ], [
                'phone.regex' => 'Enter a valid phone number e.g. 0712345678.',
            ]);

            // Update order with phone number before submission
            $order->update(['pesapal_phone' => $request->input('phone')]);

            Log::info("Submitting order to PesaPal", [
                'order_id'   => $order->id,
                'amount'     => $order->total_amount,
                'consumer'   => $order->consumer?->name,
                'email'      => $order->consumer?->email,
                'phone'      => $order->consumer?->phone,
            ]);

            $result = $this->pesapal->submitOrder($order);

            Log::info("PesaPal submitOrder result: " . json_encode($result));

            if (!$result['success']) {
                Log::error("Payment submission failed: " . $result['error']);
                return response()->json(['success' => false, 'error' => $result['error']], 422);
            }

            // Store PesaPal order tracking ID
            $order->update([
                'checkout_request_id' => $result['order_tracking_id'],
            ]);

            Log::info("Payment initiated successfully", [
                'order_id'         => $order->id,
                'tracking_id'      => $result['order_tracking_id'],
                'redirect_url'     => substr($result['redirect_url'], 0, 50) . '...',
            ]);

            return response()->json([
                'success'      => true,
                'redirect_url' => $result['redirect_url'],
            ]);
        } catch (\Throwable $e) {
            Log::error("Payment initiation exception: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * GET /orders/{id}/payment-status — polled by the browser.
     */
    public function status(int $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $this->authorizeConsumer($order);

        if ($order->payment_status === 'pending' && $order->checkout_request_id) {
            $this->resolveFromPesaPal($order);
            $order->refresh();
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status'   => $order->status,
        ]);
    }

    /**
     * Query PesaPal directly and update the order if payment is confirmed.
     */
    private function resolveFromPesaPal(Order $order): void
    {
        try {
            $status = $this->pesapal->getTransactionStatus($order->checkout_request_id);

            // Status: Completed / Failed / Invalid / Reversed
            if ($status === 'Completed') {
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'confirmed',
                ]);
                Log::info("Order #{$order->id} confirmed via PesaPal status check.");
            } elseif ($status === 'Failed') {
                $order->update(['payment_status' => 'failed']);
                Log::info("Order #{$order->id} marked failed via PesaPal.");
            }
        } catch (\Throwable $e) {
            Log::warning("PesaPal status check failed for order #{$order->id}: " . $e->getMessage());
        }
    }

    /**
     * GET/POST /api/pesapal/callback — called by PesaPal servers.
     */
    public function pesapalCallback(Request $request): RedirectResponse|string
    {
        $data = $request->all();
        Log::info('PesaPal Callback received', $data);

        try {
            // PesaPal sends either camelCase (GET) or snake_case (POST)
            $orderTrackingId = $data['OrderTrackingId'] ?? $data['order_tracking_id'] ?? null;

            if (!$orderTrackingId) {
                Log::warning('PesaPal callback: missing tracking ID', $data);
                return 'OK';
            }

            // Look up order by tracking ID (unique and reliable)
            $order = Order::where('checkout_request_id', $orderTrackingId)->first();

            if (!$order) {
                Log::warning('PesaPal callback: order not found by tracking ID', ['tracking_id' => $orderTrackingId]);
                return 'OK';
            }

            // Query PesaPal to verify payment status
            $status = $this->pesapal->getTransactionStatus($orderTrackingId);

            // Status: Completed / Failed / Invalid / Reversed
            if ($status === 'Completed') {
                $order->update([
                    'payment_status'         => 'paid',
                    'status'                 => 'confirmed',
                    'pesapal_transaction_id' => $orderTrackingId,
                ]);
                Log::info("Order #{$order->id} paid via PesaPal callback. Tracking ID: {$orderTrackingId}");
            } elseif ($status === 'Failed') {
                $order->update(['payment_status' => 'failed']);
                Log::info("Order #{$order->id} PesaPal payment failed.");
            }
        } catch (\Throwable $e) {
            Log::error('PesaPal callback exception', ['error' => $e->getMessage()]);
        }

        // Redirect to order page if we have an order ID
        if (isset($order) && $order) {
            return redirect()->route('orders.show', $order->id)->with('success', 'Payment processed. Thank you for your order!');
        }

        return 'OK';
    }

    private function authorizeConsumer(Order $order): void
    {
        if (auth()->id() !== $order->consumer_id) {
            abort(403);
        }
    }
}
