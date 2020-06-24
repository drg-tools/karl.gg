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

Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('builds', 'Api\BuildController')->only(['index', 'show']);
    Route::resource('characters', 'Api\CharacterController')->only(['index', 'show']);
    Route::resource('guns', 'Api\GunController')->only(['index', 'show']);
    Route::resource('mods', 'Api\ModController')->only(['index', 'show']);

});
