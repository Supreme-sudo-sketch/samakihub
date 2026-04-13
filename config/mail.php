<?php

return [
    'default' => env('MAIL_MAILER', 'log'),

    'mailers' => [
        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],

        'smtp' => [
            'transport'  => 'smtp',
            'scheme'     => env('MAIL_SCHEME'),
            'url'        => env('MAIL_URL'),
            'host'       => env('MAIL_HOST', '127.0.0.1'),
            'port'       => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username'   => env('MAIL_USERNAME'),
            'password'   => env('MAIL_PASSWORD'),
            'timeout'    => null,
        ],

        'array'    => ['transport' => 'array'],
        'failover' => ['transport' => 'failover', 'mailers' => []],
        'roundrobin' => ['transport' => 'roundrobin', 'mailers' => []],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@samakihub.co.ke'),
        'name'    => env('MAIL_FROM_NAME', 'SamakiHub'),
    ],
];
