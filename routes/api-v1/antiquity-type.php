<?php

use App\Http\Controllers\AntiquityTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Antiquity Type
|--------------------------------------------------------------------------
*/
Route::prefix('/antiquity-type')->controller(AntiquityTypeController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('antiquity_type.index');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Show (Working)
        Route::get('/show/{antiquity_id}', 'show')->name('antiquity_type.show')->where('{antiquity_type_id}', '[0-9]+');

        // Store (Working)
        Route::post('/store', 'store')->name('antiquity_type.store');

        // Update (Working)
        Route::patch('/update/{antiquity_type_id}', 'update')->name('antiquity_type.update')->where('{antiquity_type_id}', '[0-9]+');

        // Delete (Working)
        Route::delete('/delete/{antiquity_type_id}', 'delete')->name('antiquity_type.delete')->where('{antiquity_type_id}', '[0-9]+');

        // Restore (Working)
        Route::patch('/restore/{antiquity_type_id}', 'restore')->name('antiquity_type.restore')->where('{antiquity_type_id}', '[0-9]+');

        // Force Delete (Working)
        Route::delete('/delete/force/{antiquity_type_id}', 'forceDelete')->name('antiquity_type.force_delete')->where('{antiquity_type_id}', '[0-9]+');
    });
});
