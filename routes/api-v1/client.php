<?php

use App\Http\Controllers\ClientController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client
|--------------------------------------------------------------------------
*/
Route::prefix('/client')->controller(ClientController::class)->middleware('auth:sanctum')->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('client.index')->middleware('permission:' . Permission::ClientIndex);

    // Show (Working)
    Route::get('/show/{client_id}', 'show')->name('client.show')->middleware('permission:' . Permission::ClientShow)->where('{client_id}', '[0-9]+');

    // Update (Working)
    Route::patch('/update', 'update')->name('client.update')->middleware('role:' . Role::RoleClient);

    // Delete
    // Route::delete('/delete/{client_id}', 'destroy')->name('client.destroy')->middleware('permission:' . Permission::ClientDestroy)->where('{client_id}', '[0-9]+');

    // Disable
    // Route::patch('/disable/{client_id}', 'disable')->name('client.disable')->middleware('permission:' . Permission::ClientDisable)->where('{client_id}', '[0-9]+');
});
