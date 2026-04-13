<?php

return [
    'consumer_key'    => env('PESAPAL_CONSUMER_KEY', 'qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW'),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET', 'osGQ364R49cXKeOYSpaOnT++rHs='),
    'environment'     => env('PESAPAL_ENV', 'sandbox'),

    'sandbox_base_url'    => 'https://cybqa.pesapal.com/pesapalv3',
    'production_base_url' => 'https://pay.pesapal.com/v3',

    'ipn_id'          => env('PESAPAL_IPN_ID', ''),
    'callback_url'    => env('PESAPAL_CALLBACK_URL', 'http://localhost:8000/checkout/callback'),
    'ipn_url'         => env('PESAPAL_IPN_URL', 'http://localhost:8000/checkout/ipn'),
];
