<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */




    'paths' => ['api/*', 'sanctum/csrf-cookie', 'horizon/csrf-cookie', 'storage/*'],
    'allowed_methods' => ['*'],
    // 'allowed_origins' => ['http://localhost:5173'],
    'allowed_origins' => [
        'http://localhost:5173',
        'http://192.168.33.11:5173',
         // Backend API origins
         'http://192.168.33.11:8000',
        'http://192.168.33.11:8001',
        'http://192.168.33.11:8002',
        'http://192.168.33.11:8003',
        'http://192.168.33.11:8004',
        'http://192.168.33.11:8005',
        'http://192.168.33.11:8006', // Backend API origins
        'http://192.168.33.11:8007',
        'http://192.168.33.11:8008',
        'http://192.168.33.11:8009',
        'http://192.168.33.11:8010',
        'http://192.168.33.11:8011',
        'http://192.168.33.11:8012', // Backend API origins
        'http://192.168.33.11:8013',
        'http://192.168.33.11:8014',
        'http://192.168.33.11:8015',
        // Add other trusted origins as needed
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];


