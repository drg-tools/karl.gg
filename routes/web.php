<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\BuildMetricController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoadoutsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
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
    Route::get('profile/{id}/edit', [ProfileController::class, 'editProfile'])->where('id', '[0-9]+');

    // TODO: need to review this
    Route::post('profile/{id}/edit/update', [ProfileController::class, 'editProfileSave'])
        ->name('user.profile.update')
        ->where('id', '[0-9]+');

    Route::delete('build/delete/{id}', [LoadoutsController::class, 'delete']);
});

Route::middleware(['can:access-api'])->group(function () {
    Route::get('settings/tokens', [SettingsController::class, 'tokens'])->name('settings.tokens');
});

// Public, signed unsubscribe url
Route::get('unsubscribe/{user}', [SubscriptionController::class, 'unsubscribe'])
    ->name('unsubscribe')
    ->middleware('signed');

Route::get('/', [DashboardController::class, 'index']);
Route::get('browse', [LoadoutsController::class, 'index'])->name('loadout.index');
Route::resource('blog', PostController::class)->only(['index', 'show']);

Route::get('preview/{loadoutId}', [LoadoutsController::class, 'preview'])->name('preview.show');
Route::get('build', [LoadoutsController::class, 'build']);
Route::get('build/{loadoutId}', [LoadoutsController::class, 'build'])->name('build.show');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('asv/{gun}/{combo}', [BuildMetricController::class, 'show'])->name('asv.show')->where('gun', '[0-9]+');
Route::get('profile/{id}', [ProfileController::class, 'index'])->name('user.profile')->where('id', '[0-9]+');
Auth::routes();
