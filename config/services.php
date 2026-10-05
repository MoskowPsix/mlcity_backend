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

    'vkontakte' => [
        'client_id' => env('VKONTAKTE_CLIENT_ID'),
        'client_secret' => env('VKONTAKTE_CLIENT_SECRET'),
        'redirect' => env('VKONTAKTE_REDIRECT_URI')
    ],
    'telegram' => [
        'bot' => env('TELEGRAM_BOT_NAME'),  // The bot's username
        'client_id' => null,
        'client_secret' => env('TELEGRAM_BOT_API'),
        'redirect' => env('TELEGRAM_REDIRECT_URI'),
    ],
    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI')
    ],
    'yandex' => [
        'client_id' => env("YANDEX_CLIENT_ID"),
        'client_secret' => env("YANDEX_CLIENT_SECRET"),
        'redirect' => env("YANDEX_REDIRECT_URI")
    ],

    'mototrack' => [
        'api_key' => env('MOTOTRACK_INTEGRATION_API_KEY'),
        'user_id' => env('MOTOTRACK_INTEGRATION_USER_ID', 1),
    ],

    'mototrack_device_service' => [
        'base_url' => env('MOTOTRACK_DEVICE_SERVICE_URL', 'http://127.0.0.1:8080'),
        'token' => env('MOTOTRACK_DEVICE_SERVICE_TOKEN'),
        'timeout' => env('MOTOTRACK_DEVICE_SERVICE_TIMEOUT', 5),
        'poll_limit' => env('MOTOTRACK_DEVICE_SERVICE_POLL_LIMIT', 100),
        'last_event_id_key' => env('MOTOTRACK_DEVICE_SERVICE_LAST_EVENT_ID_KEY', 'mototrack:device-service:last_event_id'),
        'min_lap_interval_ms' => env('MOTOTRACK_RFID_MIN_LAP_INTERVAL_MS', 10000),
        'min_run_duration_ms' => env('MOTOTRACK_RFID_MIN_RUN_DURATION_MS', 5000),
    ],

    'carting' => [
        'api_key' => env('CARTING_INTEGRATION_API_KEY'),
        'user_id' => env('CARTING_INTEGRATION_USER_ID', 1),
    ],
];
