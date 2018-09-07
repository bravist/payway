<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * Laravel Signed Routes
 * 路由定义与参数作签名，签名方法需要定义路由名称
 */
Route::middleware(['client'])->get('/user', 'UserController@index')->name('api.user');

/**
 * Laravel Signed Routes
 * 1. 定义好路由名称
 * 2. 定义好APP_KEY
 */
Route::middleware(['client'])->get('/signature', 'UserController@sign');
