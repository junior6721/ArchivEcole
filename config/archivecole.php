<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ArchivEcole Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration centrale pour la plateforme ArchivEcole
    |
    */

    'app_name' => env('APP_NAME', 'ArchivEcole'),

    // Verification settings
    'verification' => [
        'default_amount' => (int) env('VERIFICATION_AMOUNT', 5000),
        'currency' => env('VERIFICATION_CURRENCY', 'XOF'),
        'otp_expiration_minutes' => (int) env('OTP_EXPIRATION', 15),
        'otp_max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 3),
        'request_expiration_hours' => (int) env('VERIFICATION_REQUEST_EXPIRATION', 24),
        'rate_limit_per_minute' => (int) env('VERIFICATION_RATE_LIMIT', 5),
    ],

    // SMS Configuration
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'ikoddi'),
        'enabled' => env('SMS_ENABLED', true),
    ],

    // Payment Configuration
    'payment' => [
        'default_provider' => env('PAYMENT_PROVIDER', 'fedapay'),
        'providers' => [
            'fedapay' => [
                'enabled' => env('FEDAPAY_ENABLED', true),
                'sandbox' => env('FEDAPAY_SANDBOX', false),
            ],
            'kkiapay' => [
                'enabled' => env('KKIAPAY_ENABLED', false),
                'sandbox' => env('KKIAPAY_SANDBOX', true),
            ],
        ],
    ],

    // File Upload Configuration
    'uploads' => [
        'disk' => env('UPLOAD_DISK', 'local'),
        'diplomas_path' => 'diplomas',
        'max_size_mb' => (int) env('UPLOAD_MAX_SIZE', 10),
        'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
    ],

    // QR Code Configuration
    'qr_code' => [
        'size' => 300,
        'error_correction' => 'M',
        'margin' => 2,
    ],

    // Audit Configuration
    'audit' => [
        'enabled' => env('AUDIT_ENABLED', true),
        'retention_days' => (int) env('AUDIT_RETENTION_DAYS', 365),
    ],
];
