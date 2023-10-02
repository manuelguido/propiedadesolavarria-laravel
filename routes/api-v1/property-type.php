<?php

use App\Http\Controllers\PropertyTypeController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Property Type
|--------------------------------------------------------------------------
*/
Route::prefix('/property-type')->controller(PropertyTypeController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('property_type.index');

    // Show (Working)
    Route::get('/show/{property_id}', 'show')->name('property_type.show')->where('{property_type_id}', '[0-9]+');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Store (Working)
        Route::post('/store', 'store')->name('property_type.store')->middleware('permission:' . Permission::PropertyTypeStore);

        // Update (Working)
        Route::patch('/update/{property_type_id}', 'update')->name('property_type.update')->middleware('permission:' . Permission::PropertyTypeUpdate)->where('{property_type_id}', '[0-9]+');

        // Destroy (Working)
        Route::delete('/delete/{property_type_id}', 'delete')->name('property_type.delete')->middleware('permission:' . Permission::PropertyTypeForceDelete)->where('{property_type_id}', '[0-9]+');
    });
});
