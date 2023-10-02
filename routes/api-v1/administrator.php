<?php

use App\Http\Controllers\AdministratorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Administrator
|--------------------------------------------------------------------------
*/
Route::prefix('/administrator')->controller(AdministratorController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('administrator.index');

    // Show (Working)
    Route::get('/show/{administrator_id}', 'show')->name('administrator.show')->where('{administrator_id}', '[0-9]+');

    // Store (Working)
    Route::post('/store', 'store')->name('administrator.store');

    // Update (Working)
    Route::patch('/update/{administrator_id}', 'update')->name('administrator.update')->where('administrator_id', '[0-9]+');

    // Delete (Working)
    Route::delete('/delete/{administrator_id}', 'delete')->name('administrator.delete')->where('{administrator_id}', '[0-9]+');
});
