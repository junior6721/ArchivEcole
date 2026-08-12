<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Audit Log Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for audit logging system
    |
    */

    'enabled' => env('AUDIT_ENABLED', true),

    'log_level' => env('AUDIT_LOG_LEVEL', 'info'),

    'retention' => [
        'enabled' => true,
        'days' => env('AUDIT_RETENTION_DAYS', 365),
    ],

    'events' => [
        'login' => true,
        'diploma_create' => true,
        'diploma_update' => true,
        'diploma_delete' => true,
        'verification_request' => true,
        'payment_processed' => true,
        'otp_generated' => true,
        'user_create' => true,
        'institution_create' => true,
    ],
];
