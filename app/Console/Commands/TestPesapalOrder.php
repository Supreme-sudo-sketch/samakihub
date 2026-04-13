<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\PesaPalService;
use Illuminate\Console\Command;

class TestPesapalOrder extends Command
{
    protected $signature = 'pesapal:test-order {order_id}';
    protected $description = 'Test PesaPal order submission for a specific order';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        try {
            $this->info("Testing PesaPal order submission for order #{$orderId}...");
            
            $order = Order::with('consumer')->findOrFail($orderId);
            
            if (!$order->consumer) {
                $this->error('✗ Order consumer not found!');
                return 1;
            }
            
            $this->info("Order data:");
            $this->line("  ID: {$order->id}");
            $this->line("  Amount: {$order->total_amount}");
            $this->line("  Consumer: {$order->consumer->name} ({$order->consumer->email})");
            
            $service = new PesaPalService();
            $result = $service->submitOrder($order);
            
            if ($result['success']) {
                $this->info('✓ Order submitted successfully!');
                $this->line("  Redirect URL: {$result['redirect_url']}");
                $this->line("  Order Tracking ID: {$result['order_tracking_id']}");
                return 0;
            } else {
                $this->error('✗ Order submission failed: ' . $result['error']);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ ERROR: ' . $e->getMessage());
            return 1;
        }
    }
}
