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
     | api token auth status
     |--------------------------------------------------------------------------
     |
     | This value determines the api token auth is enabled, or disabled.
     |
     */
    'enabled' => env('SIGNATURE_ENABLED', true),
    
    /*
     |--------------------------------------------------------------------------
     | hashing algorithm
     |--------------------------------------------------------------------------
     |
     | Specify the hashing algorithm that will be used to sign the token.
     |
     */
    'algo' => env('SIGNATURE_HMAC_ALGO', 'sha256'),
    
    /*
     |--------------------------------------------------------------------------
     | Secret Column
     |--------------------------------------------------------------------------
     |
     | The name of the secret "column" in persistent storage.
     |
     */
    'storage_key' => env('SIGNATURE_STORAGE_KEY', 'secret'),
    
    /*
    |--------------------------------------------------------------------------
    | Token Query String
    |--------------------------------------------------------------------------
    |
    | The name of the query string item from the request containing the API token.
    |
    */
    'input_key' => env('SIGNATURE_INPUT_KEY', 'api_token'),
];
