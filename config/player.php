<?php

return [
    'cache' => env('PLAYER_CACHE', storage_path('app/php-player')),
    'format' => env('PLAYER_FORMAT', 'mp3'),
    'caching_time' => env('PLAYER_CACHING_TIME', 1.5),
];
