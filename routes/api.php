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

//Driver
Route::post('driver-register', 'AuthController@register_driver');
Route::get('driver-profile/{id}', 'DriverController@get_posting_driver');
Route::post('driver-update_profile/{id_user}', 'DriverController@update');
Route::get('driver-get_posting/{id}', 'DriverController@get_posting_driver');
Route::post('driver-posting/{id}', 'DriverController@posting_driver');


//Lapak
Route::post('/lapak-register','AuthController@lapak_register');
Route::post('/lapak-update/{id}','LapakController@lapak_update');
Route::post('/lapak-tambah_menu','LapakController@lapak_tambah_menu');
Route::get('/lapak-get_menu/{id}','LapakController@lapak_get_menu');
