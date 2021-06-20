<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', 'AdminController@login')->name('login')->middleware('guest');
Route::get('/logout', 'AdminController@logout')->name('logout');
Route::post('/post-login', 'AdminController@post_login')->name('postlogin')->middleware('guest');

Route::get('/android/bantuan', 'AdminController@android_bantuan');
Route::get('/android/tes_orderan', 'AdminController@tes_orderan');

Route::group(['middleware' => ['auth']],function(){
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
    
    Route::get('/customer', 'AdminController@customer_index')->name('customer');
    Route::get('/customer', 'AdminController@customer_index')->name('customer');
    
    Route::get('/order', 'AdminController@order_index')->name('order');
    Route::get('/order/detail/{id}', 'AdminController@order_detail')->name('order.detail');
    Route::post('/order/update/{id}', 'AdminController@order_update')->name('order.update');
    Route::post('/order/delete/{id}', 'AdminController@order_delete')->name('order.delete');
    Route::get('/order/jastip/delete/{id}', 'AdminController@order_jastip_delete')->name('order.jastip.delete');
    Route::get('/order-posting', 'AdminController@order_posting_index')->name('order-posting');
    Route::post('/order-posting/delete/{id}', 'AdminController@order_posting_delete')->name('order-posting.delete');
    
    
});
