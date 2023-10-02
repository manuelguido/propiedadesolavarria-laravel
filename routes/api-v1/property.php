<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Property
|--------------------------------------------------------------------------
*/
Route::prefix('/property')->controller(PropertyController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('property.index');

    // Show (Working)
    Route::get('/show/{property_id}', 'show')->name('property.show')->where('{property_id}', '[0-9]+');

    // Store (Working)
    Route::post('/store', 'store')->name('property.store');

    // Update (Working)
    Route::patch('/update/{property_id}', 'update')->name('property.update')->where('property_id', '[0-9]+');

    // Delete (Working)
    Route::delete('/delete/{property_id}', 'delete')->name('property.delete')->where('property_id', '[0-9]+');

    // Restore (Working)
    Route::patch('/restore/{property_id}', 'restore')->name('property.restore')->where('{property_id}', '[0-9]+');

    // Force Delete (Working)
    Route::delete('/delete/force/{property_id}', 'forceDelete')->name('property.force_delete')->where('{property_id}', '[0-9]+');
});
