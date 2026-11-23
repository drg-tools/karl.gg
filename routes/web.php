<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\BuildMetricController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\Comments\CommentController;
use App\Http\Controllers\Comments\VoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoadoutsController;
use App\Http\Controllers\LoadoutVoteController;
use App\Http\Controllers\NotificationController;
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
    Route::get('settings', [AccountSettingsController::class, 'edit'])->name('account.settings');
    Route::put('settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.password.update');
    Route::put('settings/notifications', [AccountSettingsController::class, 'updateNotifications'])->name('account.notifications.update');
    Route::delete('settings', [AccountSettingsController::class, 'destroy'])->name('account.destroy');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->whereUuid('notification')->name('notifications.show');

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
Route::get('calculator', [CalculatorController::class, 'show'])->name('calculator');
Route::post('build', [BuilderController::class, 'store'])->middleware('throttle:30,1')->name('builder.store');
Route::put('build/{loadout}', [BuilderController::class, 'update'])->middleware(['auth', 'throttle:30,1'])->name('builder.update');
Route::post('build/{loadout}/vote', LoadoutVoteController::class)->middleware('auth')->name('loadout.vote');
Route::get('build/{loadoutId}', [BuilderController::class, 'create'])->whereNumber('loadoutId')->name('build.show');
Route::view('/privacy-policy', 'privacy-policy.index');
Route::get('asv/{gun}/{combo}', [BuildMetricController::class, 'show'])->name('asv.show')->where('gun', '[0-9]+');
Route::get('profile/{id}', [ProfileController::class, 'index'])->name('user.profile')->where('id', '[0-9]+');
Route::permanentRedirect('profile/{id}/edit', '/settings')->where('id', '[0-9]+');
Auth::routes();

Route::controller(CommentController::class)->prefix('comments')->name('comments.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->middleware('throttle:comments')->name('store');
    Route::post('preview', 'preview')->name('preview');
    Route::middleware('auth')->group(function () {
        Route::patch('{comment}', 'update')->name('update');
        Route::delete('{comment}', 'destroy')->name('destroy');
        Route::put('{comment}/report', 'report')->name('report');
    });
});

Route::controller(VoteController::class)->prefix('comments/{comment}')->middleware('auth')->name('comments.')->group(function () {
    Route::post('upvote', 'upvote')->name('upvote');
    Route::post('downvote', 'downvote')->name('downvote');
    Route::delete('remove-vote', 'remove')->name('remove-vote');
});
