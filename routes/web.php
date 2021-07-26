<?php

use Illuminate\Support\Facades\Route;

use App\Mail\PendaftaranAkun;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/email', 'AuthApiController@sendEmail');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', 'AuthController@login')->name('login')->middleware('guest');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/post-login', 'AuthController@post_login')->name('postlogin')->middleware('guest');

Route::get('/android/bantuan', 'AdminController@android_bantuan');
Route::get('/android/tes_orderan', 'AdminController@tes_orderan');

Route::get('/privacy_policy/idn', 'PrivacyPolicyController@web')->name('privacy_policy');
Route::get('/masukan', 'AdminController@testimoni_form')->name('testimoni.form');
Route::post('/masukan-create', 'AdminController@testimoni_create')->name('testimoni.create');

Route::group(['middleware' => ['auth', 'admin']],function(){
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/driver', 'AdminController@driver_index')->name('driver');
    Route::post('/driver/create', 'AdminController@driver_create')->name('driver.create');
    Route::get('/driver/detail/{id}', 'AdminController@driver_detail')->name('driver.detail');
    Route::post('/driver/update/{id}', 'AdminController@driver_update')->name('driver.update');
    Route::post('/driver/delete/{id}', 'AdminController@driver_delete')->name('driver.delete');
    Route::get('/driver/posting', 'AdminController@driver_posting_index')->name('driver-posting');
    Route::post('/driver/posting/delete/{id}', 'AdminController@driver_posting_delete')->name('driver-posting.delete');
    Route::post('/driver/saldo/{id}', 'AdminController@driver_tambah_saldo')->name('driver.saldo');
    
    Route::get('/lapak', 'AdminController@lapak_index')->name('lapak');
    Route::post('/lapak/create', 'AdminController@lapak_create')->name('lapak.create');
    Route::get('/lapak/detail/{id}', 'AdminController@lapak_detail')->name('lapak.detail');
    Route::post('/lapak/update/{id}', 'AdminController@lapak_update')->name('lapak.update');
    Route::post('/lapak/update-status/{id_user}', 'AdminController@lapak_update_status')->name('lapak.update-status');
    Route::post('/lapak/delete/{id}', 'AdminController@lapak_delete')->name('lapak.delete');
    Route::get('/lapak/menu', 'AdminController@lapak_menu_index')->name('lapak-menu');
    Route::post('/lapak/menu/delete/{id}', 'AdminController@lapak_menu_delete')->name('lapak-menu.delete');
    
    Route::get('/kategori_menu', 'AdminController@kategori_menu_index')->name('kategori_menu');
    Route::post('/kategori_menu/create', 'AdminController@kategori_menu_create')->name('kategori_menu.create');
    Route::post('/kategori/delete/{id}', 'AdminController@kategori_menu_delete')->name('kategori_menu.delete');
    
    Route::get('/promosi', 'AdminController@promosi_index')->name('promosi');
    Route::post('/promosi/create', 'AdminController@promosi_create')->name('promosi.create');
    Route::post('/promosi/update/{id}', 'AdminController@promosi_update')->name('promosi.update');
    Route::post('/promosi/delete/{id}', 'AdminController@promosi_delete')->name('promosi.delete');
    
    Route::get('/bantuan', 'AdminController@bantuan_index')->name('bantuan');
    Route::post('/bantuan/create', 'AdminController@bantuan_create')->name('bantuan.create');
    Route::post('/bantuan/update/{id}', 'AdminController@bantuan_update')->name('bantuan.update');
    Route::post('/bantuan/delete/{id}', 'AdminController@bantuan_delete')->name('bantuan.delete');
    
    Route::get('/customer', 'AdminController@customer_index')->name('customer');
    Route::get('/customer', 'AdminController@customer_index')->name('customer');
    
    Route::get('/order', 'AdminController@order_index')->name('order');
    Route::get('/order/detail/{id}', 'AdminController@order_detail')->name('order.detail');
    Route::post('/order/update/{id}', 'AdminController@order_update')->name('order.update');
    Route::post('/order/delete/{id}', 'AdminController@order_delete')->name('order.delete');
    Route::get('/order/jastip/delete/{id}', 'AdminController@order_jastip_delete')->name('order.jastip.delete');
    Route::get('/order-posting', 'AdminController@order_posting_index')->name('order-posting');
    Route::post('/order-posting/delete/{id}', 'AdminController@order_posting_delete')->name('order-posting.delete');
    
    Route::resource('/privacy_policy', 'PrivacyPolicyController');
    Route::get('/admin-testimoni', 'AdminController@testimoni_admin')->name('testimoni');
    Route::post('/admin-testimoni/delete/{id}', 'AdminController@testimoni_delete')->name('testimoni.delete');
    
    Route::get('/promo_ongkir', 'AdminController@promo_ongkir')->name('promo_ongkir');
    Route::post('/promo_ongkir/create', 'AdminController@promo_ongkir_create')->name('promo_ongkir.create');
    Route::post('/promo_ongkir/update/{id}', 'AdminController@promo_ongkir_update')->name('promo_ongkir.update');
    Route::post('/promo_ongkir/delete/{id}', 'AdminController@promo_ongkir_delete')->name('promo_ongkir.delete');
    
    Route::get('/broadcast_notif', 'AdminController@broadcast_notif')->name('broadcast_notif');
    Route::post('/broadcast_notif/create', 'AdminController@broadcast_notif_create')->name('broadcast_notif.create');
    Route::post('/broadcast_notif/update/{id}', 'AdminController@broadcast_notif_update')->name('broadcast_notif.update');
    Route::post('/broadcast_notif/delete/{id}', 'AdminController@broadcast_notif_delete')->name('broadcast_notif.delete');
    
    
});

    // Order Offline
Route::group(['middleware' => ['auth', 'admin_order_offline']],function(){
    
    Route::get('/dashboard-Admin_order_offline', 'OrderOfflineController@dashboard')->name('admin_order_offline.dashboard');
    Route::get('/order_offline','OrderOfflineController@index')->name('kelola.order-offline');
    
    Route::get('/order_offline/create', 'OrderOfflineController@create')->name('admin_order_offline.create');
    Route::post('/order_offline/store', 'OrderOfflineController@store')->name('admin_order_offline.store');
    Route::post('/order_offline/update/{id}', 'OrderOfflineController@update')->name('admin_order_offline.update');
    Route::post('/order_offline/delete/{id}', 'OrderOfflineController@order_offline_delete')->name('admin_order_offline.delete');
    
    Route::get('autocomplete', 'OrderOfflineController@autocomplete')->name('autocomplete');
    // Api
    // Route::get('/get_ongkir','OrderOfflineController@get_ongkir')->name('get_ongkir');
    
    // Route::post('/order_offline_create', 'OrderOfflineController@order_offline_create')->name('order_offline_create');
    // Route::get('/cek_notelp_customer', 'OrderOfflineController@cek_notelp_customer')->name('cek_notelp_customer');
    
    // Route::get('/lihat_order_offline', 'OrderOfflineController@lihat_order_offline')->name('lihat_order_offline');
    // Route::get('/order_offline-cek_diterima_driver','OrderOfflineController@order_offline_cek_diterima_driver')->name('order_offline_cek_diterima_driver');
    
    // Route::post('/order_offline-driver_terima_order/{id_order_offline}', 'OrderOfflineController@order_offline_driver_terima_order')->name('order_offline_driver_terima_order');
    
    // Route::get('/cari_lapak_offline', 'OrderOfflineController@cari_lapak_offline')->name('cari_lapak_offline');
    
    // Route::get('/get-kecamatan', 'OrderOfflineController@kecamatan')->name('get-kecamatan');

});

