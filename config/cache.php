<?php

return [
    'views' => [
        'enabled' => $enabled = env('CACHE_VIEWS'),
        'path' => $enabled ? base_path('cache/views') : false
    ]
];
