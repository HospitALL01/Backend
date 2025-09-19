<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Allowing API routes and Sanctum CSRF Cookie
    
    'allowed_methods' => ['*'], // Allows all HTTP methods (GET, POST, etc.)
    
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'), // Default frontend URL from .env
        'http://localhost:5173',  // Local development server
        'http://127.0.0.1:5173', // Another local development server
    ],
    
    'allowed_origins_patterns' => [], // Optional: allow specific patterns if needed
    
    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'Origin',
    ],
    
    'exposed_headers' => [
        'Authorization',
        'Content-Type',
    ],
    
    'max_age' => 3600, // Cache the preflight request for 1 hour
    'supports_credentials' => true, // Ensure cookies (if using) are sent
];
