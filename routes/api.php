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
// Route::post('/driver-register', 'AuthController@driver_register');
Route::post('/driver-update/{id_user}', 'DriverApiController@update');
Route::post('/driver-posting/{id}', 'DriverApiController@driver_posting');
Route::get('/driver-profile/{id}', 'DriverApiController@profile');
Route::get('/driver-get_posting/{id}', 'DriverApiController@get_posting_driver');

//Lapak
// Route::post('/lapak-register','AuthController@lapak_register');
Route::post('/lapak-update/{id_user}','LapakController@lapak_update');
Route::post('/lapak-tambah_menu','LapakController@lapak_tambah_menu');
Route::get('/lapak-get_menu/{id}','LapakController@lapak_get_menu');
Route::get('/lapak-get_profil/{id}','LapakApiController@lapak_get_profil');

//Customer
Route::post('/customer-register','AuthApiController@customer_register');
Route::post('/customer-update_profile/{id}','CustomerApiController@customer_update_profile');
Route::get('/customer-get_profil/{id}','CustomerApiController@customer_get_profil');
Route::get('/customer-get_menu_all','CustomerApiController@customer_get_menu_all');
Route::get('/customer-cari_menu','CustomerApiController@customer_cari_menu');
Route::get('/customer-get_detail_menu/{id}','CustomerApiController@customer_get_detail_menu');
Route::get('/customer-get_detail_lapak/{id_lapak}','CustomerApiController@customer_get_detail_lapak');
Route::get('/customer-get_menu_lapak/{id_lapak}','CustomerApiController@customer_get_menu_lapak');
