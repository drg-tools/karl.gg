<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web','role:super-admin'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('mod', 'ModCrudController');
    Route::crud('mod-stat', 'ModStatCrudController');
    Route::crud('overclock', 'OverclockCrudController');
    Route::crud('gun', 'GunCrudController');
    Route::crud('build', 'BuildCrudController');
    Route::crud('character', 'CharacterCrudController');
}); // this should be the absolute last line of this file