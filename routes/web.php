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
Route::get('/logout', 'AdminController@login')->name('logout')->middleware('guest');
Route::post('/post-login', 'AdminController@post_login')->name('postlogin')->middleware('guest');

Route::group(['middleware' => ['auth']],function(){
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/driver', 'AdminController@driver_index')->name('driver');
    Route::post('/driver/create', 'AdminController@driver_create')->name('driver.create');
    Route::get('/lapak', 'AdminController@lapak_index')->name('lapak');
    Route::post('/lapak/create', 'AdminController@lapak_create')->name('lapak.create');
    Route::get('/customer', 'AdminController@customer_index')->name('customer');

});
