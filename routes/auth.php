<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HowItWorks;
use App\Http\Controllers\InvitationCode;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['revalidate']], function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])
        ->name('loginForm');
    Route::post('login', [LoginController::class, 'login'])
        ->name('login');
    Route::post('logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::group(['middleware' => ['guest']], function() {
        // Generate and verify code routes
        Route::get('invitation-code', [InvitationCode::class, 'showInvitationCodeForm'])
            ->name('invitationCodeForm');
        Route::post('invitation-code', [InvitationCode::class, 'sendInvitationCode'])
            ->name('sendInvitationCode');
        Route::post('verify-code', [InvitationCode::class, 'verifyCode'])
            ->name('verifyCode');

        // if user enter valid code
        Route::get('how-it-works', [HowItWorks::class, 'show'])
            ->name('howItWorks');
        Route::get('payment', [HowItWorks::class, 'paymentShow'])
            ->name('paymentShow');

        // Register
        Route::get('create-profile', [RegisterController::class, 'showRegistrationForm'])
            ->name('registerForm');
        Route::post('sign-up', [RegisterController::class, 'register'])
            ->name('register');
    });
});



