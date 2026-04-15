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

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'register',
        'logout',
        'broadcasting/auth',
        'lost-items/*',
        'found-items/*',
        'matches/*',
        'messages/*',
        'notifications/*',
        'profile/*',
        'dashboard/*',
        'admin/*',
        'map/*',
    ],

    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, PUT, DELETE, OPTIONS)

    'allowed_origins' => ['*'], // Allow all origins for development

    'allowed_origins_patterns' => [], // You can add regex patterns if needed

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'Authorization',
        'Accept',
        'Origin',
        'X-HTTP-Method-Override',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [], // Headers that can be exposed to the browser

    'max_age' => 0, // How long preflight requests can be cached (in seconds)

    'supports_credentials' => true, // Allow cookies and HTTP authentication

];