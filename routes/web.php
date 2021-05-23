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
    Route::get('profile/{id}/edit', 'ProfileController@editProfile')->where('id', '[0-9]+');

    // TODO: need to review this
    Route::post('profile/{id}/edit/update', 'ProfileController@editProfileSave')
        ->name('user.profile.update')
        ->where('id', '[0-9]+');
});

Route::middleware(['can:access-api'])->group(function () {
    Route::get('settings/tokens', 'SettingsController@tokens')->name('settings.tokens');
});

// Public, signed unsubscribe url
Route::get('unsubscribe/{user}', 'SubscriptionController@unsubscribe')
    ->name('unsubscribe')
    ->middleware('signed');

Route::get('/', 'DashboardController@index');
Route::get('browse', 'LoadoutsController@index')->name('loadout.index');
Route::resource('blog', 'PostController')->only(['index', 'show']);
Route::get('/user/getUsers','LoadoutsController@getUsersForSearch')->name('users.getUsers');

Route::get('preview/{loadoutId}', 'LoadoutsController@preview')->name('preview.show');
Route::get('build', 'LoadoutsController@build');
Route::get('build/{loadoutId}', 'LoadoutsController@build')->name('build.show');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('asv/{gun}/{combo}', 'BuildMetricController@show')->name('asv.show')->where('gun', '[0-9]+');
Route::get('profile/{id}', 'ProfileController@index')->name('user.profile')->where('id', '[0-9]+');
Auth::routes();
