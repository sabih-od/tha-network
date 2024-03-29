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
           Route::post('verify-stripe-charge', [InvitationCode::class, 'verifyStripeCharge'])
               ->name('verifyStripeCharge');
        Route::post('create-stripe-checkout-session', [InvitationCode::class, 'createStripeCheckoutSession'])
            ->name('createStripeCheckoutSession');
        Route::get('forgot-password', [InvitationCode::class, 'showForgotPasswordForm'])
            ->name('forgotPasswordForm');
        Route::post('get-credentials', [InvitationCode::class, 'getCredentials'])
            ->name('getCredentials');

        // if user enter valid code
        Route::get('how-it-works', [HowItWorks::class, 'show'])
            ->name('howItWorks');
        Route::get('payment', [HowItWorks::class, 'paymentShow'])
            ->name('paymentShow');
        Route::get('stripe-payment', [HowItWorks::class, 'stripePaymentShow'])
            ->name('stripePaymentShow');
        Route::get('success-payment', [HowItWorks::class, 'successPayment'])
            ->name('successPayment')->middleware('is.client.secret');
        Route::get('stripe-success-payment', [HowItWorks::class, 'stripeSuccessPayment'])
            ->name('stripeSuccessPayment');

        // Register
        Route::get('create-profile', [RegisterController::class, 'showRegistrationForm'])
            ->name('registerForm');
        Route::post('sign-up', [RegisterController::class, 'register'])
            ->name('register');
    });
});

Route::group(['middleware' => ['guest']], function() {
    // visit by clicking invite
    Route::get('join/{username}', [InvitationCode::class, 'join'])
        ->name('joinByInvite');
    // Register with invite link (without invitation code)
});



