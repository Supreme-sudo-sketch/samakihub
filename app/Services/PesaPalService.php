<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesaPalService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private ?string $accessToken = null;

    public function __construct()
    {
        // Pick sandbox or production base URL automatically
        $env = config('pesapal.environment');
        $this->baseUrl = $env === 'sandbox'
            ? config('pesapal.sandbox_base_url')      // https://cybqa.pesapal.com/pesapalv3
            : config('pesapal.production_base_url');   // https://pay.pesapal.com/v3

        $this->consumerKey = config('pesapal.consumer_key');
        $this->consumerSecret = config('pesapal.consumer_secret');
    }

    /**
     * Get access token for API calls
     */
    public function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $url = $this->baseUrl . '/api/Auth/RequestToken';

            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($url, [
                'consumer_key'    => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Token request failed: ' . $response->body());
            }

            $this->accessToken = $response->json('token');

            if (!$this->accessToken) {
                throw new \Exception('No token in PesaPal response');
            }

            return $this->accessToken;
        } catch (\Throwable $e) {
            Log::error('PesaPal token error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Register IPN (run once and save ipn_id to config)
     */
    public function registerIPN(): string
    {
        try {
            $url = $this->baseUrl . '/api/URLSetup/RegisterIPN';
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'url'                      => config('pesapal.ipn_url'),
                    'ipn_notification_type'    => 'POST',
                ]);

            if (!$response->successful()) {
                throw new \Exception('IPN registration failed: ' . $response->body());
            }

            $ipnId = $response->json('ipn_id');
            Log::info("PesaPal IPN registered: {$ipnId}");

            return $ipnId;
        } catch (\Throwable $e) {
            Log::error('PesaPal registerIPN error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Submit order for payment
     */
    public function submitOrder(Order $order): array
    {
        try {
            $url = $this->baseUrl . '/api/Transactions/SubmitOrderRequest';
            $token = $this->getAccessToken();

            // Generate unique request ID (alphanumeric only, no special chars)
            $requestId = 'ORD' . $order->id . strtoupper(bin2hex(random_bytes(6)));

            $payload = [
                'id'              => $requestId,
                'currency'        => 'KES',
                'amount'          => $order->total_amount,
                'description'     => 'AquaHub Order #' . $order->id,
                'callback_url'    => config('pesapal.callback_url'),
                'notification_id' => config('pesapal.ipn_id'),
                'request_id'      => $requestId,
                'billing_address' => [
                    'email_address' => $order->consumer->email ?? 'customer@example.com',
                    'phone_number'  => $order->consumer->phone ?? '0700000000',
                    'first_name'    => explode(' ', $order->consumer->name ?? 'Customer')[0],
                    'last_name'     => explode(' ', ($order->consumer->name ?? 'Customer') . ' ')[1] ?? 'User',
                    'country_code'  => 'KE',
                ],
            ];

            \Log::info("PesaPal request payload", $payload);

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($url, $payload);

            \Log::info("PesaPal response status: " . $response->status());
            \Log::info("PesaPal response body: " . $response->body());

            $data = $response->json();

            // Check if PesaPal returned an error (even with HTTP 200)
            if (isset($data['error']) && is_array($data['error'])) {
                $errorMsg = $data['error']['message'] ?? 'Unknown error';
                throw new \Exception('PesaPal error: ' . $errorMsg);
            }

            if (!$response->successful()) {
                throw new \Exception('Order submission failed: ' . $response->body());
            }

            \Log::info("PesaPal order submitted successfully: " . json_encode($data));

            return [
                'success'              => true,
                'redirect_url'         => $data['redirect_url'] ?? null,
                'order_tracking_id'    => $data['order_tracking_id'] ?? null,
                'error'                => null,
            ];
        } catch (\Throwable $e) {
            \Log::error('PesaPal submitOrder error: ' . $e->getMessage());

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(string $orderTrackingId): string
    {
        try {
            $url = $this->baseUrl . '/api/Transactions/GetTransactionStatus';
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($url . '?orderTrackingId=' . urlencode($orderTrackingId));

            if (!$response->successful()) {
                throw new \Exception('Status query failed: ' . $response->body());
            }

            // Returns: Completed / Failed / Invalid / Reversed
            $status = $response->json('payment_status_description');

            \Log::info("PesaPal status query for {$orderTrackingId}: {$status}");

            return $status;
        } catch (\Throwable $e) {
            \Log::error('PesaPal getTransactionStatus error: ' . $e->getMessage());
            throw $e;
        }
    }
}
