<?php

use App\Http\Controllers\ModeratorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Moderator
|--------------------------------------------------------------------------
*/
Route::prefix('/moderator')->controller(ModeratorController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('moderator.index');

    // Show (Working)
    Route::get('/show/{moderator_id}', 'show')->name('moderator.show')->where('{moderator_id}', '[0-9]+');

    // Store (Working)
    Route::post('/store', 'store')->name('moderator.store');

    // Update (Working)
    Route::patch('/update/{moderator_id}', 'update')->name('moderator.update')->where('{moderator_id}', '[0-9]+');

    // Delete (Working)
    Route::delete('/delete/{moderator_id}', 'delete')->name('moderator.delete')->where('{moderator_id}', '[0-9]+');
});
