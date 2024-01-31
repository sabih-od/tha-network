<?php

use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CmsController as FrontCmsController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HowItWorks;
use App\Http\Controllers\InvitationCode;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\StripeController;
use App\Models\Page;
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

Route::get('/temp', function () {
//    $users = \App\Models\User::all();
//        foreach ($users as $user){
//            $user->remaining_referrals = 25;
//            $user->save();
//        }

//    \App\Models\NetworkMember::updateOrCreate([
//        'user_id' => 'd6adccb7-a642-4fc9-9cb9-237d296be29b',
//        'network_id' => '8377417f-bdf8-42f4-85ce-ceee2c3120c8'
//    ]);
//
//    \App\Models\NetworkMember::updateOrCreate([
//        'user_id' => '177cd65b-883b-4ef9-aaf0-bfd35dd6e5bd',
//        'network_id' => '18dedf0e-2682-4e82-835f-db7243d3b991'
//    ]);
})->name('temp');

Route::get('get/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::get('test:key'));
});
Route::get('set/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::set('test:key', 'this test'));
});
Route::get('del/redis', function () {
    dd(\Illuminate\Support\Facades\Redis::del('test:key'));
});

//ADMIN LOGIN
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->middleware('guest')->name('admin.login');

Route::namespace('App\Http\Controllers\Admin')->prefix('/admin')->middleware('admin')->group(function () {
    //Dashboard
    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');

    //setting
    Route::match(['get', 'post'], '/settings', 'SettingController@index')->name('settings');
    route::get('/changePassword', [SettingController::class, 'changePassword']);
    route::post('/updateAdminPassword', [SettingController::class, 'updateAdminPassword']);

    //payouts
    Route::match(['get', 'post'], '/payouts', 'SettingController@payouts')->name('payouts');
    Route::get('/connect-stripe-account', [\App\Http\Controllers\Admin\StripeController::class, 'connectAccount'])->name('admin.connectStripePayoutAccount');

    //goal
    Route::match(['get', 'post'], '/goals', 'GoalController@index')->name('admin.goals');

    //user
    Route::get('users', 'UserController@index')->name('admin.users');
    Route::delete('users/destroy/{id}', 'UserController@destroy')->name('admin.user.delete');
    Route::get('users/suspend/{id}', 'UserController@suspend')->name('admin.user.suspend');
    Route::get('users/close/{id}', 'UserController@close')->name('admin.user.close');
    Route::get('users/detail/{id}', 'UserController@detail')->name('admin.user.detail');
    Route::get('user-posts/{id}', 'UserController@userPosts')->name('admin.user.userPosts');
    Route::get('user-comments/{id}', 'UserController@userComments')->name('admin.user.userComments');
    Route::get('user-rewards/{id}', 'UserController@userRewards')->name('admin.user.userRewards');
    Route::delete('user-posts/destroy/{id}', 'UserController@postDestroy');

    //deleted-user
    Route::get('deleted-users', 'DeletedUserController@index')->name('admin.deleted-users');
    Route::post('deleted-users/retrieve/{id}', 'DeletedUserController@retrieve');

    //suspended-user
    Route::get('suspended-users', 'SuspendedUserController@index')->name('admin.suspended-users');
    Route::post('suspended-users/retrieve/{id}', 'SuspendedUserController@retrieve');

    //closed-user
    Route::get('closed-users', 'ClosedUserController@index')->name('admin.closed-users');
    Route::post('closed-users/retrieve/{id}', 'ClosedUserController@retrieve');

    // Customer
    Route::resource('customers', 'CustomersController');
    Route::delete('/customers/destroy/{id}', 'CustomersController@destroy')->name('customers.destroy');

    //cms
    Route::match(['get', 'post'], '/cms/home', [CmsController::class, 'home'])->name('admin.cms.home');
    Route::match(['get', 'post'], '/cms/about-us', [CmsController::class, 'aboutUs'])->name('admin.cms.aboutUs');
    Route::match(['get', 'post'], '/cms/benefits', [CmsController::class, 'benefits'])->name('admin.cms.benefits');
    Route::match(['get', 'post'], '/cms/terms', [CmsController::class, 'terms'])->name('admin.cms.terms');
    Route::match(['get', 'post'], '/cms/privacy', [CmsController::class, 'privacy'])->name('admin.cms.privacy');
    Route::match(['get', 'post'], '/cms/contact', [CmsController::class, 'contact'])->name('admin.cms.contact');

    //admin withdrawal
    Route::post('admin-withdrwal', [AdminWithdrawalController::class, 'create'])->name('admin.admin_withdrawal.create');
});

//Inertia routes
require "auth.php";
Route::group([
    'middleware' => ['auth', 'revalidate', 'closure', 'suspension']
], function () {
    Route::get('edit-profile/{pmu?}', [ProfileController::class, 'edit'])
        ->name('editProfileForm');
    Route::post('edit-profile', [ProfileController::class, 'update'])
        ->name('updateProfile');
    //Monthly payment route
    Route::get('monthly-success-payment', [HowItWorks::class, 'monthlySuccessPayment'])
        ->name('monthlySuccessPayment');
});
Route::group([
    'middleware' => ['auth', 'revalidate', 'suspension', 'closure', 'has.provided.stripe.info']
], function () {
    // home page
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // profile routes
    Route::get('profile', [ProfileController::class, 'show'])
        ->name('profile');
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
    // Comment Like
    Route::post('comment/like', [PostController::class, 'commentLikeToggle'])
        ->name('commentLikeToggle');
    // Reply Like
    Route::post('reply/like', [PostController::class, 'replyLikeToggle'])
        ->name('replyLikeToggle');

    // User follow/unfollow
    Route::post('user/follow', [ProfileController::class, 'userFollowToggle'])
        ->name('userFollowToggle');

    // User block/unblock
    Route::post('user/block', [ProfileController::class, 'userBlockToggle'])
        ->name('userBlockToggle');

    // Post comment
    Route::post('post/comment', [PostController::class, 'postCommentStore'])
        ->name('postCommentStore');
    Route::post('post/comment/delete', [PostController::class, 'postCommentDelete'])
        ->name('postCommentDelete');
    // Comment reply
    Route::post('comment/reply', [PostController::class, 'commentReplyStore'])
        ->name('commentReplyStore');

    //invite other users
    Route::post('send-invitation-code', [InvitationCode::class, 'sendInvitation'])
        ->name('sendInvitation');

    // Chat routes
    Route::get('chat', [ChatController::class, 'index'])
        ->name('chatIndex');
    Route::post('chat/message', [ChatController::class, 'chatMessageStore'])
        ->name('chatMessageStore');
    Route::delete('chat/destroy', [ChatController::class, 'chatMessageDestroy'])
        ->name('chatMessageDestroy');

    // Channel routes
    Route::post('channel/create', [ChannelController::class, 'store'])
        ->name('channelStore');
    Route::delete('channel/destroy', [ChannelController::class, 'channelDestroy'])
        ->name('channelDestroy');

    // Notification routes
    Route::post('channel-notification-viewed/{id}', [ChannelController::class, 'viewedNotification'])
        ->name('channelNotificationViewed');
    Route::post('clear-notifications', [NotificationController::class, 'clearNotifications'])
        ->name('clearNotifications');
    Route::post('delete-notification', [NotificationController::class, 'deleteNotification'])
        ->name('deleteNotification');


    // Friend Request routes
    Route::get('send-request/{target_id}', [FriendRequestController::class, 'sendRequest'])
        ->name('sendRequest');
    Route::get('accept-request/{target_id}', [FriendRequestController::class, 'acceptRequest'])
        ->name('acceptRequest');
    Route::get('reject-request/{target_id}', [FriendRequestController::class, 'rejectRequest'])
        ->name('rejectRequest');
    Route::get('unfriend/{target_id}', [FriendRequestController::class, 'unfriend'])
        ->name('unfriend');
    Route::get('block/{target_id}', [FriendRequestController::class, 'block'])
        ->name('block');
    Route::get('unblock/{target_id}', [FriendRequestController::class, 'unblock'])
        ->name('unblock');

    // Close My Account
    Route::post('close-my-account', [ProfileController::class, 'closeMyAccount'])
        ->name('closeMyAccount')->withoutMiddleware('has.provided.stripe.info');

    //connect stripe
    Route::get('connect-stripe', [StripeController::class, 'connectAccount'])
        ->name('connect-stripe')->withoutMiddleware('has.provided.stripe.info');
    Route::post('connect-paypal', [StripeController::class, 'connectPaypalAccount'])
        ->name('connect-paypal')->withoutMiddleware('has.provided.stripe.info');
    //stripe portal
    Route::post('create-stripe-portal-session', [InvitationCode::class, 'createStripePortalSession'])
        ->name('createStripePortalSession')->withoutMiddleware('has.provided.stripe.info');
});

Route::middleware('has.provided.stripe.info')->group(function () {
    Route::get('/home', function () {
        $check = session()->has('validate-code');
//    if ($check)
//        session()->remove('validate-code');
        $home = Page::where('name', 'Home')->first();
        $data = json_decode($home->content ?? []);

        return Inertia::render('HowItWorks', [
            'visitedByCode' => $check,
            'data' => $data
        ]);
    })->name('work');

    Route::get('/about', [FrontCmsController::class, 'about'])->name('about');

    Route::get('/contact', [FrontCmsController::class, 'contact'])->name('contact');

    Route::get('/privacy', [FrontCmsController::class, 'privacy'])->name('privacy');

    Route::get('/terms', [FrontCmsController::class, 'terms'])->name('terms');

    Route::get('/benefits', [FrontCmsController::class, 'benefits'])->name('benefits');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
