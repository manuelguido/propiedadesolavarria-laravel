<?php

use App\Http\Controllers\FavouritePostController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Favourite Post
|--------------------------------------------------------------------------
*/
Route::prefix('/favourite-post')->controller(FavouritePostController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('favouritePost.index');

    // Store (Working)
    Route::post('/store', 'store')->name('favouritePost.store');

    // Destroy (Working)
    Route::delete('/delete/force/{favourite_post_id}', 'forceDelete')->name('favouritePost.force.delete')->where('{favourite_post_id}', '[0-9]+');

    // Store in collection
    // Route::post('/store-in-collection', 'storeInCollection')->name('favouritePost.storeInCollection')->middleware('permission:' . Permission::FavouritePostStore);

    // Delete from collection
    // Route::delete('/delete-from-collection', 'deleteFromCollection')->name('favouritePost.deleteFromCollection')->middleware('permission:' . Permission::FavouritePostDestroy);
});
