<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Register create
    Route::get('register', [RegisteredUserController::class, 'create'])
		->name('register');

    // Register store
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login create
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
		->name('login');

    // Login store
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot password create
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Forgot password store
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

	// Reset password create
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

	// Reset password store create
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
	// Verify email
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

	// Forgot password create
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

	// Email verification store
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

	// Confirm password show
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

	// Confirm password store
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

	// Password update
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

	// Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
