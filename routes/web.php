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

Route::middleware(['role:super-admin'])->group(function () {
    Route::get('settings/tokens', 'SettingsController@tokens')->name('settings.tokens');
});

Route::get('/', 'DashboardController@index');
Route::get('browse', 'LoadoutsController@index')->name('loadout.index');

Route::view('preview/{loadoutId}', 'loadouts.preview')->name('preview.show');
Route::view('build', 'loadouts.create');
Route::view('build/{loadoutId}', 'loadouts.create');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('profile/{id}', 'ProfileController@index')->name('user.profile')->where('id', '[0-9]+');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['role:super-admin'],
    'namespace' => '\Backpack\PermissionManager\app\Http\Controllers',
], function () {
    Route::crud('permission', 'PermissionCrudController');
    Route::crud('role', 'RoleCrudController');
    Route::crud('user', 'UserCrudController');
});
