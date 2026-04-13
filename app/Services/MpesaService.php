<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $callbackUrl;
    private string $baseUrl;

    public function __construct()
    {
        $this->consumerKey   = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortcode     = config('mpesa.shortcode');
        $this->passkey       = config('mpesa.passkey');
        $this->callbackUrl   = config('mpesa.callback_url');
        $this->baseUrl       = config('mpesa.base_urls.' . config('mpesa.env'));
    }

    /**
     * Get OAuth access token (cached for ~58 minutes, scoped to env).
     */
    public function getAccessToken(): string
    {
        $cacheKey = 'mpesa_access_token_' . config('mpesa.env');

        return Cache::remember($cacheKey, 3500, function () {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->timeout(30)
                ->get("{$this->baseUrl}/oauth/v1/generate", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                Log::error('M-Pesa OAuth failed', ['body' => $response->body()]);
                throw new \RuntimeException('Could not authenticate with M-Pesa. Check your credentials.');
            }

            return $response->json('access_token');
        });
    }

    /**
     * Initiate STK Push (Lipa Na M-Pesa Online).
     *
     * @return array{success: bool, CheckoutRequestID?: string, MerchantRequestID?: string, error?: string}
     */
    public function stkPush(string $phone, float $amount, int $orderId): array
    {
        // Mock mode: simulate a successful STK push for local dev (no real Safaricom call)
        if (config('mpesa.mock')) {
            Log::info('M-Pesa mock STK Push', ['phone' => $phone, 'amount' => $amount, 'order_id' => $orderId]);
            return [
                'success'           => true,
                'CheckoutRequestID' => 'mock-checkout-' . $orderId . '-' . time(),
                'MerchantRequestID' => 'mock-merchant-' . $orderId,
            ];
        }

        try {
            $token     = $this->getAccessToken();
            $timestamp = now()->format('YmdHis');
            $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);
            $phone     = $this->formatPhone($phone);

            $response = Http::withToken($token)
                ->timeout(30)
                ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                    'BusinessShortCode' => $this->shortcode,
                    'Password'          => $password,
                    'Timestamp'         => $timestamp,
                    'TransactionType'   => 'CustomerPayBillOnline',
                    'Amount'            => (int) ceil($amount),
                    'PartyA'            => $phone,
                    'PartyB'            => $this->shortcode,
                    'PhoneNumber'       => $phone,
                    'CallBackURL'       => $this->callbackUrl,
                    'AccountReference'  => 'SamakiHub-' . $orderId,
                    'TransactionDesc'   => 'Payment for Order #' . $orderId,
                ]);

            $data = $response->json();
            Log::info('M-Pesa STK Push response', $data);

            if (isset($data['ResponseCode']) && $data['ResponseCode'] === '0') {
                return [
                    'success'           => true,
                    'CheckoutRequestID' => $data['CheckoutRequestID'],
                    'MerchantRequestID' => $data['MerchantRequestID'],
                ];
            }

            return [
                'success' => false,
                'error'   => $data['errorMessage'] ?? $data['ResponseDescription'] ?? 'STK Push failed.',
            ];
        } catch (\Throwable $e) {
            Log::error('M-Pesa STK Push exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Query status of an STK Push request.
     *
     * @return array{ResultCode?: string, ResultDesc?: string}
     */
    public function stkQuery(string $checkoutRequestId): array
    {
        try {
            $token     = $this->getAccessToken();
            $timestamp = now()->format('YmdHis');
            $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

            $response = Http::withToken($token)
                ->timeout(30)
                ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", [
                    'BusinessShortCode' => $this->shortcode,
                    'Password'          => $password,
                    'Timestamp'         => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('M-Pesa STK Query exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Normalize phone to 254XXXXXXXXX format.
     */
    public function formatPhone(string $phone): string
    {
        // Strip leading + before removing non-digits, so +254... is handled correctly
        $phone = ltrim(trim($phone), '+');
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}
