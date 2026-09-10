<?php

$defaultOrigins = [
    'https://vokrug.city',
    'https://www.vokrug.city',
    'https://api.vokrug.city',
    'http://localhost:8100',
    'http://127.0.0.1:8100',
    'http://localhost:4200',
    'http://127.0.0.1:4200',
];

$envOrigins = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('CORS_ALLOWED_ORIGINS', ''))
)));

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | With supports_credentials=true browsers reject Access-Control-Allow-Origin: *.
    | List concrete frontend origins (prod + local) instead.
    |
    */

    'paths' => ['api/*', 'storage/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['POST', 'GET', 'OPTIONS', 'PUT', 'PATCH', 'DELETE'],

    'allowed_origins' => $envOrigins !== [] ? $envOrigins : $defaultOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'Accept',
        'X-XSRF-TOKEN',
        'X-Requested-With',
        'X-Socket-Id',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
