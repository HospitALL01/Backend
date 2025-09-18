<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | এখানে আমরা React (Vite) ফ্রন্টএন্ড থেকে Laravel API কলগুলোকে সেফলি
    | allow করছি। FRONTEND_URL না দিলে নিচের fallback গুলো কাজ করবে।
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    // সব HTTP মেথড allow
    'allowed_methods' => ['*'],

    // origin whitelist (env + common fallbacks)
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://127.0.0.1:5173'),
        'http://127.0.0.1:5173',
        'http://localhost:5173',
        'http://127.0.0.1:3000',
        'http://localhost:3000',
    ],

    'allowed_origins_patterns' => [],

    // সব হেডার allow (Authorization, Content-Type সহ)
    'allowed_headers' => ['*'],

    // browser কে কোন হেডার expose করবে
    'exposed_headers' => ['Authorization', 'Content-Type'],

    // preflight cache age (seconds)
    'max_age' => 3600,

    // cookie/credential allow লাগলে true; শুধু Bearer token header এর জন্য true/false দুটোতেই হবে
    'supports_credentials' => true,
];
