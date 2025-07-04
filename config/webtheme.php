<?php

return [
    'default' => env('WEBTHEME', 'default'),
    'active' => env('WEBTHEME_ACTIVE', 'default'),
    'paths' => [
        'views' => env('WEBTHEME_VIEWS_PATH', 'resources/views/themes'),
        'assets' => env('WEBTHEME_ASSETS_PATH', 'public/themes'),  
        'stubs' => env('WEBTHEME_STUBS_PATH', 'resources/stubs/webtheme')
    ]
];