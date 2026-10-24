<?php

return [
    'frontend_origins' => array_values(array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'https://sitetrack.pro'))))),
];
