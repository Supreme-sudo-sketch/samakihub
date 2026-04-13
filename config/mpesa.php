<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-Pesa Daraja API Configuration
    |--------------------------------------------------------------------------
    | Get credentials at https://developer.safaricom.co.ke
    | env: 'sandbox' for testing, 'production' for live
    */

    'env'              => env('MPESA_ENV', 'sandbox'),
    'consumer_key'     => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret'  => env('MPESA_CONSUMER_SECRET', ''),

    // Lipa Na M-Pesa (STK Push) shortcode — use 174379 for sandbox
    'shortcode'        => env('MPESA_SHORTCODE', '174379'),

    // Lipa Na M-Pesa passkey — get from Daraja sandbox portal
    'passkey'          => env('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),

    // Must be a publicly reachable HTTPS URL (use ngrok for local dev)
    'callback_url'     => env('MPESA_CALLBACK_URL', ''),

    'base_urls' => [
        'sandbox'    => 'https://sandbox.safaricom.co.ke',
        'production' => 'https://api.safaricom.co.ke',
    ],

    // Set MPESA_MOCK=true for local dev when Safaricom blocks your IP.
    // Simulates a successful STK push so you can test the full UI flow.
    // Always false in production.
    'mock' => env('MPESA_MOCK', false),
];
