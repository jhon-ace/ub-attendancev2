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
        'http://localhost:5173',
         // Backend API origins
        'http://localhost:8000',
        'http://localhost:8001',
        'http://localhost:8002',
        'http://localhost:8003',
        'http://localhost:8004',
        'http://localhost:8005',
        'http://localhost:8006', // Backend API origins
        'http://localhost:8007',
        'http://localhost:8008',
        'http://localhost:8009',
        'http://localhost:8010',
        'http://localhost:8011',
        'http://localhost:8012', // Backend API origins
        'http://localhost:8013',
        'http://localhost:8014',
        'http://localhost:8015',
        // Add other trusted origins as needed
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];


