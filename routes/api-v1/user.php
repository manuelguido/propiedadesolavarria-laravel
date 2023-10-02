<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
Route::prefix('/user')->controller(UserController::class)->middleware('auth:sanctum')->group(function () {

    // User update (Working)
    Route::patch('/update', 'update')->name('user.update');

    // User password update (Working)
    Route::patch('/password/update', 'passwordUpdate')->name('user.password.update');
});
