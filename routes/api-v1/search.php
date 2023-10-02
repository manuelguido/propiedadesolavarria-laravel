<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| post
|--------------------------------------------------------------------------
*/
Route::prefix('/search')->controller(SearchController::class)->group(function () {

    Route::get('/parameters', 'searchParameters')->name('search.parameters');

    Route::get('/posts', 'searchPosts')->name('search.posts');

    // Route::middleware('auth:sanctum')->group(function () {

    // });
});
