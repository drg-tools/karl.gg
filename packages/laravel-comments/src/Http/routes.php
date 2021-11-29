<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'web',
    'namespace' => 'Hazzard\Comments\Http\Controllers',
], function () {
    // Admin routes
    Route::post('comments/admin/logout', 'AdminController@logout');
    Route::get('comments/admin', 'AdminController@dashboard')->name('comments.dashboard');
    Route::get('comments/admin/settings', 'AdminController@settings')->name('comments.settings');
    Route::post('comments/admin/settings', 'AdminController@updateSettings');
    Route::delete('comments/admin/settings', 'AdminController@resetSettings');

    // Comments routes
    Route::resource('comments', 'CommentController');
    Route::post('comments/preview', 'CommentController@preview');
    Route::put('comments/{id}/report', 'CommentController@report');
    Route::post('comments/bulk-update', 'CommentController@bulkUpdate');

    // Comment votes routes
    Route::post('comments/{id}/upvote', 'VoteController@upvote');
    Route::post('comments/{id}/downvote', 'VoteController@downvote');
    Route::delete('comments/{id}/remove-vote', 'VoteController@remove');
});
