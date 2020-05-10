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

Route::middleware(['auth'])->group(function () {
    Route::get('my-builds', 'BuildController@myBuilds')->name('builds.my');
    Route::get('favorites', 'BuildController@favorites')->name('builds.favorites');
    Route::resource('builds', 'BuildController')->except(['index', 'show']);
    Route::resource('builds.favorites', 'Build\FavoriteController')->only('store');
});

Route::resource('builds', 'BuildController')->only(['index', 'show']);
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('/', 'BuildController@index');
Auth::routes();



//Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
