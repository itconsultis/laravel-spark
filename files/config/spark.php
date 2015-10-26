<?php

return [

    'urls' => [

        'iterator' => 'naive',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Request $_SERVER Parameters
    |--------------------------------------------------------------------------
    |
    | $_SERVER defaults
    |
    */

    'server_parameters' => [
        'SERVER_NAME' => 'localhost',
        'SERVER_PORT' => 80,
        'HTTP_HOST' => 'localhost',
        'HTTP_USER_AGENT' => 'Symfony/2.X',
        'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
        'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'REMOTE_ADDR' => '127.0.0.1',
        'SERVER_PROTOCOL' => 'HTTP/1.1',
    ],

];
