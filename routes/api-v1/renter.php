<?php

use App\Http\Controllers\RenterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Renter
|--------------------------------------------------------------------------
*/
Route::prefix('/renter')->controller(RenterController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('renter.index');

    // Show (Working)
    Route::get('/show/{renter_id}', 'show')->name('renter.show')->where('{renter_id}', '[0-9]+');

    // Authenticated
    Route::controller(RenterController::class)->middleware('auth:sanctum')->group(function () {

        // Store (Working)
        Route::post('/store', 'store')->name('renter.store');

        // Update (Working)
        Route::patch('/update/{renter_id}', 'update')->name('renter.update')->where('{renter_id}', '[0-9]+');

        // Delete (Working)
        Route::delete('/delete/{renter_id}', 'delete')->name('renter.delete')->where('{renter_id}', '[0-9]+');
    });
});
