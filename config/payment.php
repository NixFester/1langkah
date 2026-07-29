<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Driver
    |--------------------------------------------------------------------------
    |
    | This value controls the payment gateway used for processing transactions.
    | Supported: "mock", "xendit"
    |
    */
    'driver' => env('PAYMENT_DRIVER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Xendit Configuration
    |--------------------------------------------------------------------------
    |
    | These values are used when PAYMENT_DRIVER is set to "xendit".
    | You can find your API key in the Xendit Dashboard.
    |
    */
    'xendit' => [
        'api_key' => env('XENDIT_API_KEY'),
        'callback_token' => env('XENDIT_CALLBACK_TOKEN'),
        'environment' => env('XENDIT_ENVIRONMENT', 'development'), // "development" or "production"
    ],

    /*
    |--------------------------------------------------------------------------
    | Mock Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the mock payment driver used during development/testing.
    |
    */
    'mock' => [
        'auto_approve' => env('MOCK_PAYMENT_AUTO_APPROVE', true),
    ],
];
