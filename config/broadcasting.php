<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    */

    'default'     => env('BROADCAST_DRIVER', 'pusher'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher'  => [
            'driver' => 'pusher',
            'key'    => env('PUSHER_KEY'),
            'secret' => env('PUSHER_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
        ],

        // LOLOLOL - We Pushman doesn't use its own driver xD
        // Just kidding, Pushman doesn't need to push to itself right?
        // uses its internal port :P

        // erugh, alright fine, might as well declare it at least.
        'pushman' => [
            'driver'  => 'pushman',
            'private' => env('PUSHMAN_PRIVATE'),
            'url'     => env('PUSHMAN_URL')
        ],

        'redis'   => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        'log'     => [
            'driver' => 'log',
        ],

    ],

];
