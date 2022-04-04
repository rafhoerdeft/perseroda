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

Route::middleware('preventBackHistory')->get('/', [App\Http\Controllers\Login::class, 'index'])->name('auth');

Route::prefix('/')->name('auth.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('auth', 'Login@index');
    Route::post('auth', 'Login@process')->name('process');
    Route::get('logout', 'Login@logout')->name('logout');
});

Route::middleware(['preventBackHistory', 'auth.user:akuntansi;bendahara;kasir'])->get('dashboard', [App\Http\Controllers\Dashboard::class, 'index'])->name('dash');

Route::middleware('preventBackHistory')->prefix('barang')->name('barang.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('', 'Barang@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
    Route::get('add', 'Barang@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
    Route::get('edit/{id?}', 'Barang@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
    Route::post('delete', 'Barang@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
    Route::post('save', 'Barang@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
});

Route::middleware('preventBackHistory')->prefix('order')->name('order.')->namespace('App\Http\Controllers\Order')->group(function () {
    Route::middleware('preventBackHistory')->prefix('perdagangan')->name('perdagangan.')->group(function () {
        Route::get('', 'Perdagangan@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('data/{status?}/{jenis?}', 'Perdagangan@getData')->name('data')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('add', 'Perdagangan@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('barang', 'Perdagangan@getBarang')->name('barang')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('edit/{id?}', 'Perdagangan@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('delete', 'Perdagangan@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('save', 'Perdagangan@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
    });
    Route::middleware('preventBackHistory')->prefix('percetakan')->name('percetakan.')->group(function () {
        Route::get('', 'Percetakan@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('add', 'Percetakan@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('edit/{id?}', 'Percetakan@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('delete', 'Percetakan@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('save', 'Percetakan@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
    });
    Route::middleware('preventBackHistory')->prefix('jasa')->name('jasa.')->group(function () {
        Route::get('', 'Jasa@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('add', 'Jasa@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::get('edit/{id?}', 'Jasa@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('delete', 'Jasa@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
        Route::post('save', 'Jasa@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
    });
});

Route::middleware('preventBackHistory')->prefix('all')->name('all.')->namespace('App\Http\Controllers')->group(function () {
    Route::post('delete', 'All@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
});
