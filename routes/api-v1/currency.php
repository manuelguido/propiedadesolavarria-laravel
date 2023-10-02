<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Currency
|--------------------------------------------------------------------------
*/
Route::prefix('/currency')->controller(CurrencyController::class)->group(function () {

    // Index (Working)
    Route::get('/index', 'index')->name('currency.index');

    // Show (Working)
    Route::get('/show/{currency_id}', 'show')->name('currency.show')->where('{currency_id}', '[0-9]+');
});
