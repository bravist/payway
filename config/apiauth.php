<?php

/*
 * This file is part of api-auth.
 *
 * Zhang Lu <zhanglu@ruoyubuy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
     |--------------------------------------------------------------------------
     | api token auth service config
     |--------------------------------------------------------------------------
     */
    'service' => [
        /*
         |--------------------------------------------------------------------------
         | api token auth status
         |--------------------------------------------------------------------------
         |
         | This value determines the api token auth is enabled, or disabled.
         |
         */
        'enabled' => env('API_AUTH_ENABLED', true),
        
        /*
         |--------------------------------------------------------------------------
         | hashing algorithm
         |--------------------------------------------------------------------------
         |
         | Specify the hashing algorithm that will be used to sign the token.
         |
         */
        'algo' => env('API_AUTH_SERVICE_HMAC_ALGO', 'sha256'),
        
        /*
         |--------------------------------------------------------------------------
         | Secret Column
         |--------------------------------------------------------------------------
         |
         | The name of the secret "column" in persistent storage.
         |
         */
        'storage_key' => env('API_AUTH_STORAGE_KEY', 'secret'),
        
        /*
        |--------------------------------------------------------------------------
        | Token Query String
        |--------------------------------------------------------------------------
        |
        | The name of the query string item from the request containing the API token.
        |
        */
        'input_key' => env('API_AUTH_INPUT_KEY', 'api_token'),
    ],
    
    /*
     |--------------------------------------------------------------------------
     | api token auth client config
     |--------------------------------------------------------------------------
     */
    'client' => [
        
        /*
         |--------------------------------------------------------------------------
         | hashing algorithm
         |--------------------------------------------------------------------------
         |
         | Specify the hashing algorithm that will be used to sign the token.
         |
         */
        'algo' => env('API_AUTH_CLIENT_HMAC_ALGO', 'sha256'),
        
        /*
         |--------------------------------------------------------------------------
         | Authentication App ID
         |--------------------------------------------------------------------------
         |
         | Don't forget to set this in your .env file.
         |
         */
        'appid' => env('API_AUTH_APPID'),
        
        /*
         |--------------------------------------------------------------------------
         | Authentication Secret
         |--------------------------------------------------------------------------
         |
         | Don't forget to set this in your .env file, as it will be used to sign
         | your tokens. A helper command is provided for this:
         | `php artisan apiauth:secret`
         |
         | Note: This will be used for Symmetric algorithms only (HMAC).
         |
         */
        'secret' => env('API_AUTH_SECRET'),
    ],
];
