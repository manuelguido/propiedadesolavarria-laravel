<?php

use App\Http\Controllers\SurfaceMeasurementTypeController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Surface Measruement Type
|--------------------------------------------------------------------------
*/
Route::prefix('/surface-measurement-type')->controller(SurfaceMeasurementTypeController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('surface_measurement_type.index');

    // Show (Working)
    Route::get('/show/{surface_measurement_type_id}', 'show')->name('surface_measurment_type.show')->where('{surface_measurement_type_id}', '[0-9]+');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {

        // Store (Working)
        Route::post('/store', 'store')->name('surface_measurement_type.store')->middleware('permission:' . Permission::SurfaceMeasurementTypeStore);

        // Update (Working)
        Route::patch('/update/{surface_measurement_type_id}', 'update')->name('surface_measurement_type.update')->middleware('permission:' . Permission::SurfaceMeasurementTypeUpdate)->where('{surface_measurement_type_id}', '[0-9]+');

        // Destroy (Working)
        Route::delete('/delete/{surface_measurement_type_id}', 'delete')->name('surface_measurement_type.delete')->middleware('permission:' . Permission::SurfaceMeasurementTypeForceDelete)->where('{surface_measurement_type_id}', '[0-9]+');
    });
});
