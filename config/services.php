<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mercado_pago' => [
        'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
        'runtime_mode' => env('MERCADO_PAGO_RUNTIME_MODE'),
        'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
        'idempotency_key' => env('MERCADO_PAGO_IDEMPOTENCY_KEY'),
        'webhook_secret' => env('MERCADO_PAGO_WEBHOOK_SECRET'),
    ],

    'moodle' => [
        'url' => env('MOODLE_URL'),
        'token' => env('MOODLE_TOKEN'),
    ]
];
