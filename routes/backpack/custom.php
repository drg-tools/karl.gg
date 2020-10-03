<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'role:super-admin'],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('mod', 'ModCrudController');
    Route::crud('patch', 'PatchCrudController');
    Route::crud('overclock', 'OverclockCrudController');
    Route::crud('gun', 'GunCrudController');
    Route::crud('equipment', 'EquipmentCrudController');
    Route::crud('equipment-mod', 'EquipmentModCrudController');
    Route::crud('throwable', 'ThrowableCrudController');
    Route::crud('loadout', 'LoadoutCrudController');
    Route::crud('character', 'CharacterCrudController');
    Route::get('charts/daily-users', 'Charts\DailyUsersChartController@response')->name('charts.daily-users.index');
    Route::get('charts/daily-loadouts', 'Charts\DailyLoadoutsChartController@response')->name('charts.daily-loadouts.index');
    Route::crud('post', 'PostCrudController');
}); // this should be the absolute last line of this file