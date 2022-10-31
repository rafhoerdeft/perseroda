<?php

use App\Http\Controllers\Config\Logo;
use App\Http\Controllers\Order\Perdagangan;
use App\Http\Livewire\Base;
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

Route::middleware(['preventBackHistory', 'isLogin'])->get('/', [App\Http\Controllers\Login::class, 'index'])->name('auth');

Route::prefix('/')->name('auth.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('auth', 'Login@index')->middleware(['preventBackHistory', 'isLogin']);
    Route::post('auth', 'Login@process')->name('process')->middleware(['preventBackHistory', 'isLogin']);
    Route::get('logout', 'Login@logout')->name('logout');
});

Route::middleware(['preventBackHistory', 'auth.user'])->get('dashboard', [App\Http\Controllers\Dashboard::class, 'index'])->name('dash');
// Route::middleware(['preventBackHistory', 'auth.user:main|akuntansi-bendahara;second|kasir'])->get('dashboard', [App\Http\Controllers\Dashboard::class, 'index'])->name('dash');

Route::middleware('preventBackHistory')->prefix('produk')->name('produk.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('', 'Produk@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
    Route::get('add', 'Produk@add')->name('add')->middleware('auth.user:akuntansi;bendahara');
    Route::get('edit/{id?}', 'Produk@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara');
    Route::post('delete', 'Produk@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara');
    Route::post('save', 'Produk@save')->name('save')->middleware('auth.user:akuntansi;bendahara');
});

Route::middleware('preventBackHistory')->prefix('rekanan')->name('rekanan.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('', 'Rekanan@index')->name('list')->middleware('auth.user:akuntansi;bendahara');
    Route::get('add', 'Rekanan@add')->name('add')->middleware('auth.user:akuntansi;bendahara');
    Route::get('edit/{id?}', 'Rekanan@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara');
    Route::post('delete', 'Rekanan@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara');
    Route::post('save', 'Rekanan@save')->name('save')->middleware('auth.user:akuntansi;bendahara');
});

Route::middleware('preventBackHistory')->prefix('transaksi')->name('transaksi.')->group(function () {
    Route::prefix('in')->name('in.')->namespace('App\Http\Controllers\Transaksi\In')->group(function () {
        Route::prefix('perdagangan')->name('perdagangan.')->group(function () {
            Route::get('', 'Perdagangan@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('data/{status?}/{jenis?}', 'Perdagangan@getData')->name('data')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('add', 'Perdagangan@add')->name('add')->middleware('auth.user:kasir');
            Route::get('produk', 'Perdagangan@getProduk')->name('produk')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('edit/{id?}', 'Perdagangan@edit')->name('edit')->middleware('auth.user:kasir');
            Route::post('delete', 'Perdagangan@delete')->name('delete')->middleware('auth.user:kasir');
            Route::post('delete/all', 'Perdagangan@deleteAll')->name('delete.all')->middleware('auth.user:kasir');
            Route::post('save', 'Perdagangan@save')->name('save')->middleware('auth.user:kasir');
            Route::prefix('change')->name('change.')->group(function () {
                Route::post('statusbayar', 'Perdagangan@changeStatusBayar')->name('statusbayar')->middleware('auth.user:akuntansi;bendahara;kasir');
                Route::post('jenisbayar', 'Perdagangan@changeJenisBayar')->name('jenisbayar')->middleware('auth.user:akuntansi;bendahara;kasir');
            });
            Route::get('print/{id?}', 'Perdagangan@printNota')->name('print')->middleware('auth.user:kasir');
        });
        Route::prefix('percetakan')->name('percetakan.')->group(function () {
            Route::get('', 'Percetakan@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('data/{status?}/{jenis?}', 'Percetakan@getData')->name('data')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('add', 'Percetakan@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('produk', 'Percetakan@getProduk')->name('produk')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('edit/{id?}', 'Percetakan@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::post('delete', 'Percetakan@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::post('delete/all', 'Percetakan@deleteAll')->name('delete.all')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::post('save', 'Percetakan@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::prefix('change')->name('change.')->group(function () {
                Route::post('statusbayar', 'Percetakan@changeStatusBayar')->name('statusbayar')->middleware('auth.user:akuntansi;bendahara;kasir');
                Route::post('jenisbayar', 'Percetakan@changeJenisBayar')->name('jenisbayar')->middleware('auth.user:akuntansi;bendahara;kasir');
            });
        });
        Route::prefix('jasa')->name('jasa.')->group(function () {
            Route::get('', 'Jasa@index')->name('list')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('add', 'Jasa@add')->name('add')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::get('edit/{id?}', 'Jasa@edit')->name('edit')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::post('delete', 'Jasa@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
            Route::post('save', 'Jasa@save')->name('save')->middleware('auth.user:akuntansi;bendahara;kasir');
        });
    });

    Route::middleware('preventBackHistory')->prefix('out')->name('out.')->namespace('App\Http\Controllers\Transaksi\Out')->group(function () {
        Route::prefix('nota')->name('nota.')->group(function () {
            Route::get('', 'Nota@index')->name('list')->middleware('auth.user:akuntansi;bendahara');
            Route::get('data/{status?}/{jenis?}', 'Nota@getData')->name('data')->middleware('auth.user:akuntansi;bendahara');
            Route::get('add', 'Nota@add')->name('add')->middleware('auth.user:akuntansi;bendahara');
            Route::get('rekanan', 'Nota@getRekanan')->name('rekanan')->middleware('auth.user:akuntansi;bendahara');
            Route::get('edit/{id?}', 'Nota@edit')->name('edit')->middleware('auth.user:bendahara,akuntansi');
            Route::post('delete', 'Nota@delete')->name('delete')->middleware('auth.user:bendahara,akuntansi');
            Route::post('delete/all', 'Nota@deleteAll')->name('delete.all')->middleware('auth.user:bendahara,akuntansi');
            Route::post('save', 'Nota@save')->name('save')->middleware('auth.user:bendahara,akuntansi');
            Route::prefix('change')->name('change.')->group(function () {
                Route::post('statusbayar', 'Nota@changeStatusBayar')->name('statusbayar')->middleware('auth.user:akuntansi;bendahara');
                Route::post('jenisbayar', 'Nota@changeJenisBayar')->name('jenisbayar')->middleware('auth.user:akuntansi;bendahara');
            });
            Route::prefix('rincian')->name('rincian.')->group(function () {
                Route::get('list/{id?}', 'Rincian@index')->name('list')->middleware('auth.user:akuntansi;bendahara');
                Route::post('save', 'Rincian@save')->name('save')->middleware('auth.user:akuntansi;bendahara');
                Route::get('produk', 'Rincian@getProduk')->name('produk')->middleware('auth.user:akuntansi;bendahara');
                Route::post('delete', 'Rincian@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara');
                Route::post('delete/all', 'Rincian@deleteAll')->name('delete.all')->middleware('auth.user:akuntansi;bendahara');
            });
        });
    });
});

Route::middleware('preventBackHistory')->prefix('all')->name('all.')->namespace('App\Http\Controllers')->group(function () {
    Route::post('delete', 'All@delete')->name('delete')->middleware('auth.user:akuntansi;bendahara;kasir');
});

Route::get('live', Base::class)->name('base');

// STORAGE FILE ROUTE
Route::prefix('storage')->name('storage.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('image/{path_file?}', 'Storage@image')->name('image')->middleware('auth.user');
});

Route::middleware(['preventBackHistory', 'auth.user:admin'])->group(function () {
    Route::prefix('config')->name('config.')->namespace('App\Http\Controllers\Config')->group(function () {
        Route::name('logo')->resource('logo', 'Logo'); // GET, POST, PUT, DELETE
        Route::name('password')->resource('password', 'Password'); // GET, POST, PUT, DELETE
        Route::name('periode')->resource('periode', 'Periode'); // GET, POST, PUT, DELETE
    });
    Route::prefix('jabatan')->name('jabatan.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('', 'Jabatan@index')->name('list');
        Route::post('store', 'Jabatan@store')->name('store');
        Route::post('delete', 'Jabatan@delete')->name('delete');
        Route::post('delete/all', 'Jabatan@deleteAll')->name('delete.all');
    });
    Route::prefix('pegawai')->name('pegawai.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('', 'Pegawai@index')->name('list');
        Route::post('store', 'Pegawai@store')->name('store');
        Route::post('delete', 'Pegawai@delete')->name('delete');
        Route::post('delete/all', 'Pegawai@deleteAll')->name('delete.all');
    });
    Route::prefix('akun')->name('akun.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('', 'Akun@index')->name('list');
        Route::post('store', 'Akun@store')->name('store');
        Route::post('delete', 'Akun@delete')->name('delete');
        Route::post('delete/all', 'Akun@deleteAll')->name('delete.all');
    });
});
