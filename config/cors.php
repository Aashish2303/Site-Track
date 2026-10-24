<?php

return [
    'paths' => ['user/*', 'api/*', 'sanctum/csrf-cookie', 'forgot_password', 'reset-password', '/'],
    'allowed_methods' => ['GET', 'HEAD', 'POST', 'OPTIONS'],
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'https://sitetrack.pro'))))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Accept', 'Content-Type', 'X-CSRF-TOKEN', 'X-Requested-With'],
    'exposed_headers' => ['X-Correlation-ID', 'Retry-After'],
    'max_age' => 600,
    'supports_credentials' => false,
];
