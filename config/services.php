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
    'user_service' => [
        'url'   => env('USER_SERVICE_URL'),
        'token' => env('USER_SERVICE_TOKEN'),
    ],

    'api_gateway' => [
        'url'   => env('API_GATEWAY_URL'),
        'token' => env('API_GATEWAY_TOKEN'),
    ],

    'email' => [
        'provider' => env('EMAIL_PROVIDER', 'smtp'),
    ],


    'api' => [
        'token' => env('API_STATIC_TOKEN'),
    ],

    'circuitb' => [
        'threshold' => (int) env('CB_THRESHOLD', 5),
        'window'    => (int) env('CB_WINDOW', 60),    
        'open_ttl'  => (int) env('CB_OPEN_TTL', 300),    
    ],

    'template' => [
        'url'   => env('TEMPLATE_SERVICE_URL'),
        'token' => env('TEMPLATE_SERVICE_TOKEN'),
        'timeout' => (int) env('TEMPLATE_SERVICE_TIMEOUT', 5), 
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

];
