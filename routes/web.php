<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return Inertia::render('Welcome');
});*/

Route::get('/temp', function() {
//    dd('asd');
});

Route::get('get/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::get('test:key'));
});
Route::get('set/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::set('test:key', 'this test'));
});
Route::get('del/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::del('test:key'));
});

require "auth.php";

Route::group([
    'middleware' => ['auth', 'revalidate']
], function () {
    // home page
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // profile routes
    Route::get('profile', [ProfileController::class, 'show'])
        ->name('profile');
    Route::get('edit-profile', [ProfileController::class, 'edit'])
        ->name('editProfileForm');
    Route::post('edit-profile', [ProfileController::class, 'update'])
        ->name('updateProfile');
    Route::post('profile-image-upload', [ProfileController::class, 'profileImgUpload'])
        ->name('profileImgUpload');
    Route::post('profile-cover-upload', [ProfileController::class, 'profileCoverUpload'])
        ->name('profileCoverUpload');

    // another user profile
    Route::get('profile/{id}', [ProfileController::class, 'userProfile'])
        ->name('userProfile');

    // Post routes
    Route::post('post/create', [PostController::class, 'store'])
        ->name('postCreate');
    Route::delete('post/{id}/delete', [PostController::class, 'destroy'])
        ->name('postDelete');
    Route::post('post/share', [PostController::class, 'sharePost'])
        ->name('sharePost');

    // Post Like
    Route::post('post/like', [PostController::class, 'postLikeToggle'])
        ->name('postLikeToggle');

    // User follow/unfollow
    Route::post('user/follow', [ProfileController::class, 'userFollowToggle'])
        ->name('userFollowToggle');

    // User block/unblock
    Route::post('user/block', [ProfileController::class, 'userBlockToggle'])
        ->name('userBlockToggle');

    // Post comment
    Route::post('post/comment', [PostController::class, 'postCommentStore'])
        ->name('postCommentStore');
    Route::delete('post/comment/delete', [PostController::class, 'postCommentDelete'])
        ->name('postCommentDelete');
    // Comment reply
    Route::post('comment/reply', [PostController::class, 'commentReplyStore'])
        ->name('commentReplyStore');

    // Chat routes
    Route::get('chat', [ChatController::class, 'index'])
        ->name('chatIndex');
//    Route::post('chat/message', [ChatController::class, 'chatMessageStore'])
//        ->name('chatMessageStore');
//    Route::delete('chat/destroy', [ChatController::class, 'chatMessageDestroy'])
//        ->name('chatMessageDestroy');

    // Channel routes
    /*Route::post('channel/create', [ChannelController::class, 'store'])
        ->name('channelStore');*/
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
