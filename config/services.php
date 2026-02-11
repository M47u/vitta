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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mercadopago' => [
        'sandbox' => env('MERCADOPAGO_SANDBOX', true),

        // Selecciona automáticamente las credenciales según el modo (sandbox o producción)
        'public_key' => env('MERCADOPAGO_SANDBOX', true)
            ? env('MERCADOPAGO_TEST_PUBLIC_KEY')
            : env('MERCADOPAGO_PROD_PUBLIC_KEY'),

        'access_token' => env('MERCADOPAGO_SANDBOX', true)
            ? env('MERCADOPAGO_TEST_ACCESS_TOKEN')
            : env('MERCADOPAGO_PROD_ACCESS_TOKEN'),

        // Credenciales individuales (para referencia)
        'test_public_key' => env('MERCADOPAGO_TEST_PUBLIC_KEY'),
        'test_access_token' => env('MERCADOPAGO_TEST_ACCESS_TOKEN'),
        'prod_public_key' => env('MERCADOPAGO_PROD_PUBLIC_KEY'),
        'prod_access_token' => env('MERCADOPAGO_PROD_ACCESS_TOKEN'),
    ],

    'mercadoenvios' => [
        'zip_code_from' => env('MERCADOENVIOS_ZIP_CODE_FROM', '1636'),
    ],

];