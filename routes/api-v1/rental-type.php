<?php

use App\Http\Controllers\RentalTypeController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rental Type
|--------------------------------------------------------------------------
*/
Route::prefix('/rental-type')->controller(RentalTypeController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('rental_type.index');

    // Show (Working)
    Route::get('/show/{rental_type_id}', 'show')->name('rental_type.show');

    // Athenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Store (Working)
        Route::post('/store', 'store')->name('rental_type.store')->middleware('permission:' . Permission::RentalTypeStore);

        // Update (Working)
        Route::patch('/update/{rental_type_id}', 'update')->name('rental_type.update')->middleware('permission:' . Permission::RentalTypeUpdate)->where('{rental_type_id}', '[0-9]+');

        // Destroy (Working)
        Route::delete('/delete/{rental_type_id}', 'delete')->name('rental_type.delete')->middleware('permission:' . Permission::RentalTypeForceDelete)->where('{rental_type_id}', '[0-9]+');
    });
});
