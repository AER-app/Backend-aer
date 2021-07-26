<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Mail\PendaftaranAkun;

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

// Route::get('kirimemail', function () {
//     \Mail::raw('Selamat datang Aer Indonesia', function ($message) {
//         $message->to('fianade8@gmail.com', 'Aer');
//         $message->subject('Berhasil membuat akun');
//     });
// });

//testimoni
Route::post('/testimoni','AuthApiController@testimoni');

Route::post('/user-otp', 'AuthApiController@cek_otp');

Route::post('/customer-aktivasi_otp/{id_user}', 'AuthApiController@aktivasi_otp');
Route::post('/lupa-password', 'AuthApiController@lupa_password');
Route::post('/otp-lupa_password/{id_user}', 'AuthApiController@otp_lupa_password');

Route::post('/logout/{id_user}', 'AuthApiController@logout');

//Driver
// Route::post('/driver-register', 'AuthController@driver_register');
Route::post('/driver-login', 'AuthApiController@driver_login');
Route::post('/driver-update/{id_user}', 'DriverApiController@update');
Route::post('/driver-posting/{id_user}', 'DriverApiController@driver_posting');
Route::get('/driver-profile/{id_user}', 'DriverApiController@profile');
Route::get('/driver-get_posting/{id_user}', 'DriverApiController@get_posting_driver');
Route::post('/driver-delete_posting/{id}','DriverApiController@driver_delete_posting');
Route::get('/driver-lihat_order/{id_driver}', 'DriverApiController@driver_lihat_order');
Route::post('/driver-aktif/{id_driver}', 'DriverApiController@driver_aktif');
Route::post('/driver-update_lokasi/{id_driver}', 'DriverApiController@driver_update_lokasi');
Route::post('/driver-tutup_jastip/{id_order}', 'DriverApiController@driver_tutup_jastip');
Route::post('/driver-antar_order_posting/{id_posting}', 'DriverApiController@driver_antar_order_posting');

//Lapak
Route::post('/lapak-login', 'AuthApiController@lapak_login');
Route::get('/lapak-register','AuthApiController@lapak_register');
Route::post('/lapak-postregister','AuthApiController@lapak_postregister');
Route::get('/lapak-get_profile/{id}','LapakApiController@lapak_get_profile');
Route::post('/lapak-update/{id_user}','LapakApiController@lapak_update');
Route::get('/lapak-jadwal/{id_lapak}','LapakApiController@lapak_jadwal');
Route::post('/lapak-update_jadwal/{id_jadwal}','LapakApiController@lapak_update_jadwal');

Route::get('/lapak-get_kategori','LapakApiController@lapak_get_kategori');
Route::post('/lapak-tambah_menu','LapakApiController@lapak_tambah_menu');
Route::get('/lapak-get_menu/{id}','LapakApiController@lapak_get_menu');
Route::post('/lapak-update_menu/{id}','LapakApiController@lapak_update_menu');
Route::post('/lapak-delete_menu/{id}','LapakApiController@lapak_delete_menu');
Route::post('/lapak-tambah_posting','LapakApiController@lapak_tambah_posting');
Route::get('/lapak-get_posting_lapak/{id}','LapakApiController@lapak_get_posting_lapak');
Route::post('/lapak-update_posting/{id}','LapakApiController@lapak_update_posting');
Route::post('/lapak-delete_posting/{id}','LapakApiController@lapak_delete_posting');
Route::get('/lapak-lihat_order/{id_lapak}','LapakApiController@lapak_lihat_order');
Route::get('/lapak-lihat_jastip/{id_lapak}','LapakApiController@lapak_lihat_jastip');
Route::post('/lapak-aktif/{id_lapak}', 'LapakApiController@lapak_aktif');
Route::get('/lapak-get_detail_lapak/{id_lapak}','LapakApiController@lapak_get_detail_lapak');
Route::post('/lapak-tambah_jadwal/{id_lapak}', 'LapakApiController@lapak_tambah_jadwal');


//Customer
Route::post('/customer-login', 'AuthApiController@customer_login');
Route::post('/customer-register','AuthApiController@customer_register');
Route::post('/customer-update_profile/{id}','CustomerApiController@customer_update_profile');
Route::get('/customer-get_profile/{id}','CustomerApiController@customer_get_profil');
Route::get('/customer-get_menu_all','CustomerApiController@customer_get_menu_all');
Route::get('/customer-get_menu_diskon','CustomerApiController@customer_get_menu_diskon');
Route::get('/customer-get_menu_terdekat','CustomerApiController@customer_get_menu_terdekat');
Route::get('/customer-get_menu_terbaru','CustomerApiController@customer_get_menu_terbaru');
Route::get('/customer-get_posting_driver_all','CustomerApiController@customer_get_posting_driver_all');
Route::get('/customer-get_menu_terlaris','CustomerApiController@customer_get_menu_terlaris');
Route::post('/customer-cari_menu','CustomerApiController@customer_cari_menu');
Route::get('/customer-get_detail_menu/{id}','CustomerApiController@customer_get_detail_menu');
Route::get('/customer-get_detail_lapak/{id_lapak}','CustomerApiController@customer_get_detail_lapak');
Route::get('/customer-get_lapak_terbaru','CustomerApiController@customer_get_lapak_terbaru');
Route::get('/customer-get_menu_lapak/{id_lapak}','CustomerApiController@customer_get_menu_lapak');
Route::get('/customer-hitung','CustomerApiController@hitung');
Route::get('/customer-lihat_order/{id_customer}','CustomerApiController@customer_lihat_order');
Route::get('/customer-get_ongkir/{id}','CustomerApiController@customer_get_ongkir');
Route::get('/customer-get_posting_ongkir/{id_posting}','CustomerApiController@customer_get_posting_ongkir');
Route::get('/customer-get_menu_jenis','CustomerApiController@customer_get_menu_jenis');
Route::get('/customer-get_menu_lapak_terbaru','CustomerApiController@customer_get_menu_lapak_terbaru');
Route::get('/customer-get_kategori','CustomerApiController@customer_get_kategori');
Route::get('/customer-get_menu_kategori/{id_kategori}','CustomerApiController@customer_get_menu_kategori');
Route::get('/customer-get_menu_with_kategori/{jenis}','CustomerApiController@customer_get_menu_with_kategori');


Route::get('/customer-cari_lapak', 'CustomerApiController@customer_cari_lapak');
//Slideshow
Route::get('/customer-slideshow', 'CustomerApiController@slideshow');

//Order
Route::post('/order-tambah_order','OrderApiController@order_tambah_order');
Route::post('/order-tambah_jastip','OrderApiController@order_tambah_jastip');
Route::post('/order-tambah_order_posting','OrderApiController@order_tambah_order_posting');
Route::post('/order-tambah_order_customer_offline','OrderApiController@order_tambah_order_customer_offline');

Route::get('/order-driver_get_order','OrderApiController@order_driver_get_order');
Route::get('/order-cek_diterima_driver','OrderApiController@order_cek_diterima_driver');

Route::get('/order-driver_detail_order/{id_order}', 'OrderApiController@order_driver_detail_order');
Route::get('/order-driver_detail_jastip/{id_order}', 'OrderApiController@order_driver_detail_jastip');
Route::get('/order-driver_detail_order_posting/{id_posting}', 'OrderApiController@order_driver_detail_order_posting');
Route::get('/order-get_menu_jastip', 'OrderApiController@order_get_menu_jastip');
Route::post('/order-driver_terima_order/{id_order}', 'OrderApiController@order_driver_terima_order');
Route::post('/order-driver_kode_order/{id_order}', 'OrderApiController@order_driver_kode_order');

Route::post('/order-customer_orderan_diterima/{id_order}', 'OrderApiController@order_customer_orderan_diterima');
Route::post('/order-customer_orderan_jastip_diterima/{id_order}', 'OrderApiController@order_customer_orderan_jastip_diterima');
Route::post('/order-customer_orderan_posting_diterima/{id_order_posting}', 'OrderApiController@order_customer_orderan_posting_diterima');
Route::get('/order-customer_get_order_selesai/{id_customer}', 'OrderApiController@order_customer_get_order_selesai');
Route::get('/order-driver_get_order_selesai/{id_driver}', 'OrderApiController@order_driver_get_order_selesai');
Route::get('/order-lapak_get_order_selesai/{id_lapak}', 'OrderApiController@order_lapak_get_order_selesai');


// Order Offline

Route::get('/get_ongkir','OrderOfflineApiController@get_ongkir');

Route::post('/driver-antar_order_order_offline/{id_order_offline}', 'DriverApiController@driver_antar_order_order_offline');
Route::get('/order-driver_detail_order_offline/{id_order_offline}', 'OrderApiController@order_driver_detail_order_offline');
Route::post('/order-customer_orderan_offline_diterima/{id_order_offline}', 'OrderApiController@order_customer_orderan_offline_diterima');
Route::post('/order_offline_create', 'OrderOfflineApiController@order_offline_create');
Route::get('/cek_notelp_customer', 'OrderOfflineApiController@cek_notelp_customer');

Route::get('/lihat_order_offline', 'OrderOfflineApiController@lihat_order_offline');
Route::get('/order_offline-cek_diterima_driver','OrderOfflineApiController@order_offline_cek_diterima_driver');

Route::post('/order_offline-driver_terima_order/{id_order_offline}', 'OrderOfflineApiController@order_offline_driver_terima_order');

Route::get('/cari_lapak_offline', 'OrderOfflineApiController@cari_lapak_offline');

Route::get('/get-kecamatan', 'OrderOfflineApiController@kecamatan');

Route::post('/order_offline-driver_terima_order/{id_order_offline}', 'OrderOfflineApiController@order_offline_driver_terima_order');





