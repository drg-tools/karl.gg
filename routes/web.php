<?php

use App\Http\Controllers\BuilderController;
use App\Http\Controllers\BuildMetricController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoadoutsController;
use App\Http\Controllers\LoadoutVoteController;
use App\Http\Controllers\PickRatesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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
    Route::post('build/{id}/favorite', [LoadoutsController::class, 'toggleFavorite'])->name('loadout.favorite');
});

// Public, signed unsubscribe url
Route::get('unsubscribe/{user}', [SubscriptionController::class, 'unsubscribe'])
    ->name('unsubscribe')
    ->middleware('signed');

Route::get('/', [DashboardController::class, 'index']);
Route::get('pickrates/classes', [PickRatesController::class, 'classes'])->name('pickrates.classes');
Route::get('pickrates/guns', [PickRatesController::class, 'guns'])->name('pickrates.guns');
Route::get('pickrates/mods', [PickRatesController::class, 'mods'])->name('pickrates.mods');
Route::get('browse', [LoadoutsController::class, 'index'])->name('loadout.index');
Route::resource('blog', PostController::class)->only(['index', 'show']);

Route::get('preview/{loadoutId}', [LoadoutsController::class, 'preview'])->name('preview.show');
Route::get('build', [BuilderController::class, 'create'])->name('builder.create');
Route::get('builder/catalog/{character}', [BuilderController::class, 'catalog'])->name('builder.catalog');
Route::get('builder/metrics', [BuilderController::class, 'metrics'])->name('builder.metrics');
Route::post('build', [BuilderController::class, 'store'])->middleware('throttle:30,1')->name('builder.store');
Route::put('build/{loadout}', [BuilderController::class, 'update'])->middleware(['auth', 'throttle:30,1'])->name('builder.update');
Route::post('build/{loadout}/vote', LoadoutVoteController::class)->middleware('auth')->name('loadout.vote');
Route::get('build/{loadoutId}', [BuilderController::class, 'create'])->whereNumber('loadoutId')->name('build.show');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('asv/{gun}/{combo}', [BuildMetricController::class, 'show'])->name('asv.show')->where('gun', '[0-9]+');
Route::get('profile/{id}', [ProfileController::class, 'index'])->name('user.profile')->where('id', '[0-9]+');
Auth::routes();
