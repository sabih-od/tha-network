<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('temp', function () {
    return (dd('here'));
});

Route::group([], function () {
    //auth
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('get-invitation-code', [AuthController::class, 'getInvitationCode']);
    Route::post('verify-invitation-code', [AuthController::class, 'verifyInvitationCode']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('subscribe', [PaymentController::class, 'subscribe']);

    //secure routes
    Route::group(['middleware' => 'api2'], function () {
        //auth
        Route::get('me', [ProfileController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('send-invitation', [AuthController::class, 'sendInvitation']);
        Route::get('get-weekly-goals', [AuthController::class, 'getWeeklyGoals']);

        //profile
        Route::post('update-profile', [ProfileController::class, 'update']);
        Route::get('close-my-account', [ProfileController::class, 'closeMyAccount']);
        Route::post('update-banner', [ProfileController::class, 'updateBanner']);

        //user
        Route::post('users/search', [UserController::class, 'search']);
        Route::post('users/block', [UserController::class, 'block']);
        Route::get('new-members-this-week', [UserController::class, 'newMembersThisWeek']);
        Route::get('people-in-my-network', [UserController::class, 'peopleInMyNetwork']);

        //chat
        Route::post('chat/channels', [ChatController::class, 'channels']);
        Route::post('chat/messages', [ChatController::class, 'messages']);

        //notifications
        Route::get('notifications', [NotificationController::class, 'all']);
        Route::get('unread-notifications-count', [NotificationController::class, 'unreadNotificationsCount']);
        Route::post('notification/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::get('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

        //settings
        Route::get('settings', [SettingController::class, 'settings']);

        //post
        Route::get('posts', [PostController::class, 'list']);
        Route::post('post/create', [PostController::class, 'create']);
        Route::post('post/update/{id}', [PostController::class, 'update']);
        Route::post('post/delete/{id}', [PostController::class, 'update']);
    });
});
