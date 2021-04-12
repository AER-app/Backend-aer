<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('driver-register', 'AuthController@register_driver');
<<<<<<< HEAD
=======
Route::post('driver-update_profile', 'DriverController@update');
Route::get('driver-get_posting/{id}', 'DriverController@get_posting_driver');
Route::post('driver-posting/{id}', 'DriverController@posting_driver');

>>>>>>> parent of 8345fde... Upload foto DriverApi --Thumbnail 200

Route::post('driver-update_profile', 'DriverController@update');
Route::get('driver-get_posting/{id}', 'DriverController@get_posting_driver');
Route::post('driver-posting/{id}', 'DriverController@posting_driver');