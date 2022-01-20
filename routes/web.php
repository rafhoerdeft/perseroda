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

Route::prefix('/')->namespace('App\Http\Controllers')->group(function () {
    Route::get('auth', 'Login@index');
    Route::post('auth', 'Login@process')->name('auth.process');
    Route::get('logout', 'Login@logout')->name('auth.logout');
});

Route::middleware(['auth.user', 'preventBackHistory'])->prefix('user')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/', 'User@index')->name('user.index');
});
