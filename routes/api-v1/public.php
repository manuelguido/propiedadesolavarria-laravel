<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\RenterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public api
|--------------------------------------------------------------------------
*/

// Index (Working)
Route::get('/post/featured', [PostController::class, 'featured'])->name('post.featured');

// Index (Working)
Route::get('/post/{post_id}/related', [PostController::class, 'relatedPosts'])->name('post.related');

// Renters  (Working)
Route::get('/renter/featured', [RenterController::class, 'featured'])->name('renter.featured');
