<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => '/'], function () {
    Route::get('/', 'FileController@index')->name('file.index');
    Route::get('/{fileName}', 'FileController@show')->name('file.show');
});
