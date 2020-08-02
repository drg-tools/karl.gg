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

/*
todo: views and components
# home (index.blade.php)
## featured-foadouts (FeaturedLoadouts.vue)
### loadout-card (LoadoutCard.vue)

# browse (browse.blade.php)
## browse-loadouts (BrowseLoadouts.vue)

# overview (overview.blade.php)
## loadout-overview (LoadoutOverview.vue)

karl.gg/loadouts/<loadoutId>
# build (create.blade.php)
## class-select (ClassSelect.vue)
### class-component (ClassComponent.vue)
## equipment-select (EquipmentSelect.vue)
### equipment-component (EquipmentComponent.vue)
## stats-display (StatsDisplay.vue)
## modification-select (ModificationSelect.vue)
*/

Route::middleware(['auth'])->group(function () {
    Route::get('profile/{id}/edit', 'ProfileController@editProfile')->where('id', '[0-9]+');

    // TODO: need to review this
    Route::get('loadout/delete/{id}', 'ProfileController@deleteLoadout')->where('id', '[0-9]+');
    Route::post('profile/{id}/edit/update', 'ProfileController@editProfileSave')->name('user.profile.update')->where('id', '[0-9]+');

    Route::resource('loadouts', 'LoadoutController')->except(['index', 'show']);
    Route::resource('loadouts.favorites', 'Build\FavoriteController')->only('store');
});

Route::middleware(['role:super-admin'])->group(function () {
    Route::get('settings/tokens', 'SettingsController@tokens')->name('settings.tokens');
});

// todo: removed build view from middleware..
Route::view('browse', 'loadouts.browse');
Route::view('preview/{loadoutId}', 'loadouts.preview');
Route::view('build', 'loadouts.create');
Route::view('build/{loadoutId}', 'loadouts.create');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::view('/', 'loadouts.index');
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
