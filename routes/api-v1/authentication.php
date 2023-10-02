<?php

use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::controller(RegisterController::class)->group(function () {

    // Register (Working)
    Route::post('register', 'register');

    // Login (Working)
    Route::post('login', 'login');
});
