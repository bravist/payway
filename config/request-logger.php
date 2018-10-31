<?php
/*
|--------------------------------------------------------------------------
| Ry Request Logger Config
|--------------------------------------------------------------------------
|
|
*/
return [

    /*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    |
    | - enabled : true or false
    | - handlers: Array of the Monolog\Handler\HandlerInterface
    | - format : Format for logger output
    */
    'logger' => [
        'enabled'   => true,
        'handlers'  => ['Ry\Log\Handler\HttpLoggerHandler'],
        'format'    => 'default'
    ]
];
