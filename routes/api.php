<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
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

//auth prefix
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('get-invitation-code', [AuthController::class, 'getInvitationCode']);
    Route::post('verify-invitation-code', [AuthController::class, 'verifyInvitationCode']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    //secure routes
    Route::group(['middleware' => 'api2'], function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

//w/o auth prefix
Route::group([], function () {
    Route::post('subscribe', [PaymentController::class, 'subscribe']);
    //secure routes
//    Route::group(['middleware' => 'api2'], function () {
//    });
});
