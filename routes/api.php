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

Route::post('/user-otp', 'AuthApiController@cek_otp');

//Driver
// Route::post('/driver-register', 'AuthController@driver_register');
Route::post('/driver-login', 'AuthApiController@driver_login');
Route::post('/driver-update/{id_user}', 'DriverApiController@update');
Route::post('/driver-posting/{id_user}', 'DriverApiController@driver_posting');
Route::get('/driver-profile/{id_user}', 'DriverApiController@profile');
Route::get('/driver-get_posting/{id_user}', 'DriverApiController@get_posting_driver');
Route::post('/driver-delete_posting/{id}','DriverApiController@driver_delete_posting');
Route::get('/driver-lihat_order/{id_order}', 'DriverApiController@driver_lihat_order');

//Lapak
Route::post('/lapak-login', 'AuthApiController@lapak_login');
Route::get('/lapak-register','AuthApiController@lapak_register');
Route::post('/lapak-postregister','AuthApiController@lapak_postregister');
Route::get('/lapak-get_profile/{id}','LapakApiController@lapak_get_profile');
Route::post('/lapak-update/{id_user}','LapakApiController@lapak_update');
Route::get('/lapak-jadwal/{id_lapak}','LapakApiController@lapak_jadwal');
Route::post('/lapak-tambah_jadwal/{id_lapak}','LapakApiController@lapak_tambah_jadwal');
Route::post('/lapak-update_jadwal/{id_jadwal}','LapakApiController@lapak_update_jadwal');

Route::post('/lapak-tambah_menu','LapakApiController@lapak_tambah_menu');
Route::get('/lapak-get_menu/{id}','LapakApiController@lapak_get_menu');
Route::post('/lapak-update_menu/{id}','LapakApiController@lapak_update_menu');
Route::post('/lapak-delete_menu/{id}','LapakApiController@lapak_delete_menu');
Route::post('/lapak-tambah_posting','LapakApiController@lapak_tambah_posting');
Route::get('/lapak-get_posting_lapak/{id}','LapakApiController@lapak_get_posting_lapak');
Route::post('/lapak-update_posting/{id}','LapakApiController@lapak_update_posting');
Route::post('/lapak-delete_posting/{id}','LapakApiController@lapak_delete_posting');
Route::get('/lapak-lihat_order/{id_lapak}','LapakApiController@lapak_lihat_order');

//Customer
Route::post('/customer-login', 'AuthApiController@customer_login');
Route::post('/customer-register','AuthApiController@customer_register');
Route::post('/customer-update_profile/{id}','CustomerApiController@customer_update_profile');
Route::get('/customer-get_profile/{id}','CustomerApiController@customer_get_profil');
Route::get('/customer-get_menu_all','CustomerApiController@customer_get_menu_all');
Route::get('/customer-get_menu_diskon','CustomerApiController@customer_get_menu_diskon');
Route::get('/customer-get_menu_terbaru','CustomerApiController@customer_get_menu_terbaru');
Route::get('/customer-get_posting_driver_all','CustomerApiController@customer_get_posting_driver_all');
Route::get('/customer-get_menu_terlaris','CustomerApiController@customer_get_menu_terlaris');
Route::get('/customer-cari_menu','CustomerApiController@customer_cari_menu');
Route::get('/customer-get_detail_menu/{id}','CustomerApiController@customer_get_detail_menu');
Route::get('/customer-get_detail_lapak/{id_lapak}','CustomerApiController@customer_get_detail_lapak');
Route::get('/customer-get_lapak_terbaru','CustomerApiController@customer_get_lapak_terbaru');
Route::get('/customer-get_menu_lapak/{id_lapak}','CustomerApiController@customer_get_menu_lapak');
Route::get('/customer-hitung','CustomerApiController@hitung');
Route::get('/customer-lihat_order/{id}','CustomerApiController@customer_lihat_order');
Route::get('/customer-get_ongkir/{id}','CustomerApiController@customer_get_ongkir');
Route::get('/customer-get_posting_ongkir/{id_posting}','CustomerApiController@customer_get_posting_ongkir');


//Slideshow
Route::get('/customer-slideshow', 'CustomerApiController@slideshow');

//Order
Route::post('/order-tambah_order','OrderApiController@order_tambah_order');
Route::post('/order-tambah_jastip','OrderApiController@order_tambah_jastip');
Route::post('/order-tambah_order_customer_offline','OrderApiController@order_tambah_order_customer_offline');
Route::get('/order-driver_get_order','OrderApiController@order_driver_get_order');
Route::get('/order-driver_detail_order/{id_order}', 'OrderApiController@order_driver_detail_order');
Route::get('/order-get_menu_jastip', 'OrderApiController@order_get_menu_jastip');
Route::post('/order-driver_terima_order/{id}', 'OrderApiController@order_driver_terima_order');
Route::post('/order-driver_kode_order/{id}', 'OrderApiController@order_driver_kode_order');
Route::post('/order-customer_orderan_diterima/{id_order}', 'OrderApiController@order_customer_orderan_diterima');
Route::get('/order-customer_get_order_selesai/{id_customer}', 'OrderApiController@order_customer_get_order_selesai');
Route::get('/order-driver_get_order_selesai/{id_driver}', 'OrderApiController@order_driver_get_order_selesai');
Route::get('/order-lapak_get_order_selesai/{id_lapak}', 'OrderApiController@order_lapak_get_order_selesai');