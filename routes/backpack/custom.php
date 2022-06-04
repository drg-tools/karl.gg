<?php

use App\Http\Controllers\Admin\Charts\DailyLoadoutsChartController;
use App\Http\Controllers\Admin\Charts\DailyUpdatedLoadoutsChartController;
use App\Http\Controllers\Admin\Charts\DailyUsersChartController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'permission:view-admin'],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::group([
        'middleware' => ['web', 'permission:manage-stats'],
    ], function () {
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
    });

    Route::group([
        'middleware' => ['web', 'permission:manage-posts'],
    ], function () {
        Route::crud('post', 'PostCrudController');
    });

    Route::group([
        'middleware' => ['permission:manage-users'],
        'namespace' => '\Backpack\PermissionManager\app\Http\Controllers',
    ], function () {
        Route::crud('permission', 'PermissionCrudController');
        Route::crud('role', 'RoleCrudController');
        Route::crud('user', 'UserCrudController');
    });

    Route::get('charts/daily-users', [DailyUsersChartController::class, 'response'])->name('charts.daily-users.index');
    Route::get('charts/daily-loadouts', [DailyLoadoutsChartController::class, 'response'])->name('charts.daily-loadouts.index');
    Route::get('charts/daily-updated-loadouts', [DailyUpdatedLoadoutsChartController::class, 'response'])->name('charts.daily-updated-loadouts.index');
}); // this should be the absolute last line of this file


