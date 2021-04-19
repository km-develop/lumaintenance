<?php

return [
    'maintenance' => [
        'local' => [
            'file' => 'framework/down'
        ],
        'env' => [
            'is_down' => env('LUMAIN_IS_DOWN'),                 // If there is a value, it will switch to maintenance mode
            'allow_ips' => env('LUMAIN_ALLOW_IPS', ''),
            'exclude_path' => env('LUMAIN_EXCLUDE_PATH')
        ]
    ],

    // Used in views and API responses
    'response' => [
        'status' => 503,
        'message' => 'Sorry, Service Unavailable.'
    ],
];
