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
Route::post('driver-register', 'AuthApiController@register_driver');
Route::post('driver-update_profile', 'DriverApiController@update');
Route::get('driver-get_posting/{id}', 'DriverApiController@get_posting_driver');
Route::post('driver-posting/{id}', 'DriverApiController@posting_driver');
Route::post('driver-terima_order/{id}', 'DriverApiController@driver_terima_order');


//Lapak
Route::post('/lapak-register','AuthApiController@lapak_register');
Route::post('/lapak-update/{id_user}','LapakApiController@lapak_update');
Route::post('/lapak-tambah_menu','LapakApiController@lapak_tambah_menu');
Route::get('/lapak-get_menu/{id}','LapakApiController@lapak_get_menu');
Route::get('/lapak-get_posting_lapak/{id}','LapakApiController@lapak_get_posting_lapak');
Route::get('/lapak-get_profile/{id}','LapakApiController@lapak_get_profile');
Route::post('/lapak-tambah_posting','LapakApiController@lapak_tambah_posting');
Route::get('/lapak-get_kategori','LapakApiController@lapak_get_kategori');




//Customer
Route::post('/customer-register','AuthApiController@customer_register');
Route::get('/customer-get_profile/{id}','CustomerApiController@customer_get_profile');
Route::post('/customer-update_profile/{id}','CustomerApiController@customer_update_profile');
Route::get('/customer-get_menu_all','CustomerApiController@customer_get_menu_all');
Route::get('/customer-get_menu_terbaru','CustomerApiController@customer_get_menu_terbaru');
Route::get('/customer-get_posting_driver_all','CustomerApiController@customer_get_posting_driver_all');
Route::get('/customer-get_menu_terlaris','CustomerApiController@customer_get_menu_terlaris');
Route::get('/customer-cari_menu','CustomerApiController@customer_cari_menu');
Route::get('/customer-get_detail_menu/{id}','CustomerApiController@customer_get_detail_menu');
Route::get('/customer-get_detail_lapak/{id_lapak}','CustomerApiController@customer_get_detail_lapak');
Route::get('/customer-get_menu_lapak/{id_lapak}','CustomerApiController@customer_get_menu_lapak');
Route::get('/customer-hitung','CustomerApiController@hitung');


//Order
Route::post('/order-tambah_order','OrderApiController@order_tambah_order');
Route::post('/order-tambah_jastip','OrderApiController@order_tambah_jastip');
Route::post('/order-tambah_order_customer_offline','OrderApiController@order_tambah_order_customer_offline');
Route::get('/order-driver-get_order', 'OrderApiController@order_driver_get_order');
Route::get('/order-get_menu_jastip', 'OrderApiController@order_get_menu_jastip');
Route::post('/order-driver-terima_order/{id}', 'OrderApiController@order_driver_terima_order');


