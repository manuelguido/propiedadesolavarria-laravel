<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| post
|--------------------------------------------------------------------------
*/
Route::prefix('/post')->controller(PostController::class)->group(function () {

    // Show (Working)
    Route::get('/show/{post_id}', 'show')->name('post.show')->where('{post_id}', '[0-9]+');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Index (Working)
        Route::get('/index', 'index')->name('post.index');

        // Store (Working)
        Route::post('/store', 'store')->name('post.store');

        // Update (Working)
        Route::patch('/update/{post_id}', 'update')->name('post.update')->where('post_id', '[0-9]+');

        // Force Delete (Working)
        Route::delete('/delete/force/{post_id}', 'forceDelete')->name('post.force_delete')->where('{post_id}', '[0-9]+');
    });
});
