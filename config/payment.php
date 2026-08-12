<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FedaPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration FedaPay
    |
    */

    'enabled' => env('FEDAPAY_ENABLED', true),
    'sandbox' => env('FEDAPAY_SANDBOX', false),
    'public_key' => env('FEDAPAY_PUBLIC_KEY'),
    'private_key' => env('FEDAPAY_PRIVATE_KEY'),
    'merchant_id' => env('FEDAPAY_MERCHANT_ID'),

    /*
    |--------------------------------------------------------------------------
    | KkiaPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour KkiaPay (alternative/fallback)
    |
    */

    'kkiapay' => [
        'enabled' => env('KKIAPAY_ENABLED', false),
        'sandbox' => env('KKIAPAY_SANDBOX', true),
        'public_key' => env('KKIAPAY_PUBLIC_KEY'),
        'private_key' => env('KKIAPAY_PRIVATE_KEY'),
        'secret' => env('KKIAPAY_SECRET'),
    ],
];
