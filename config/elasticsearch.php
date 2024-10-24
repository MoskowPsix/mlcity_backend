
<?php

return [
    'enabled' => env('ELASTICSEARCH_ENABLED', false),
    'hosts' => [
        env('ELASTICSEARCH_HOSTS', 'localhost:9200'),
    ],
    'auth' => [
        'name' => env('ELASTICSEARCH_USERNAME', ''),
        'password' => env('ELASTICSEARCH_PASSWORD', ''),
    ]
];
