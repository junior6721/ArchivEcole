<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des fournisseurs SMS
    |
    */

    'default' => env('SMS_PROVIDER', 'ikoddi'),

    'providers' => [
        'ikoddi' => [
            'enabled' => env('SMS_ENABLED', true),
            'api_key' => env('IKODDI_API_KEY'),
            'organization_id' => env('IKODDI_ORGANIZATION_ID'),
            'sender' => env('IKODDI_SENDER', 'ArchivEcole'),
            'base_url' => env('IKODDI_BASE_URL', 'https://api.ikoddi.com'),
        ],

        'twilio' => [
            'enabled' => env('TWILIO_ENABLED', false),
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'phone' => env('TWILIO_PHONE_NUMBER'),
        ],
    ],

    // OTP Settings
    'otp' => [
        'length' => 6,
        'expiration' => env('OTP_EXPIRATION', 15), // minutes
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
        'resend_delay' => 60, // seconds
    ],
];
