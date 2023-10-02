<?php

use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff
|--------------------------------------------------------------------------
*/
Route::prefix('/staff')->controller(StaffController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('staff.index');

    // Show (Working)
    Route::get('/show/{staff_id}', 'show')->name('staff.show')->where('{staff_id}', '[0-9]+');

    // Store (Working)
    Route::post('/store', 'store')->name('staff.store');

    // Update (Working)
    Route::patch('/update/{staff_id}', 'update')->name('staff.update')->where('{staff_id}', '[0-9]+');

    // Delete (Working)
    Route::delete('/delete/{staff_id}', 'delete')->name('staff.delete')->where('{staff_id}', '[0-9]+');
});
