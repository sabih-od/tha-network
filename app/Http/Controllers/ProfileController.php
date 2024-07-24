<?php

namespace App\Http\Controllers;

use App\Events\NetworkMemberClosure;
use App\Events\ReferralReverted;
use App\Helpers\WebResponses;
use App\Models\FriendRequest;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\User;
use App\Traits\CommentData;
use App\Traits\PostData;
use App\Traits\StripePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Stripe\StripeClient;

class ProfileController extends Controller
{
    use PostData, CommentData, StripePayment;

    public function show(Request $request)
    {
        try {
            $user = Auth::user();
            //check if user has linked any accounts to their stripe payout screen
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $has_provided_stripe_payout_information = false;
            if ($user->stripe_account_id) {
                $account = $stripe->accounts->retrieve($user->stripe_account_id);
                $has_provided_stripe_payout_information = (bool)($account->external_accounts->total_count > 0);
            }
            return Inertia::render('Profile', [
                'user' => $user->only('id', 'username', 'email', 'created_at') ?? null,
                'profile' => $user->profile ?? null,
                'posts' => Inertia::lazy(function () {
                    return $this->getPostData(true);
                }),
                'comments' => Inertia::lazy(function () use ($request) {
                    return $this->getCommentData($request->post_id ?? null);
                }),
                'replies' => Inertia::lazy(function () use ($request) {
                    return $this->getReplyData($request->comment_id ?? null);
                }),
                'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $this->profileImg($user, 'profile_cover'),
                'friends_count' => count($user->followers),
                'network_count' => $user->network()->exists() ? count($user->network->members) : 0,
                'level_details' => get_my_level($user->id),
                'paypal_account_details' => $user->paypal_account_details,
                'has_provided_stripe_payout_information' => $has_provided_stripe_payout_information,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->with('error', $e->getMessage());
        }
    }

    private $amount;

    public function __construct()
    {
        //Commit on client demand
        //$this->amount = count(User::where('role_id', 2)->get()) < 5000 ? 29.99 : 59.95;

        $this->amount = 29.99;
    }

    public function edit(Request $request)
    {
        try {
            $user = Auth::user();
            //generate client secret (if user decides to pay for month)
            $clientSecret = $this->generateClientSecret($this->amount);
            //monthly_payment_flash (when user has made monthly subscription payment)
            $monthly_payment_flash = session()->has('monthly_payment_flash') ? session()->get('monthly_payment_flash') : null;
            //check for if user has made monthly payment or not
            $has_made_monthly_payment = has_made_monthly_payment();
            session()->remove('monthly_payment_flash');

            //check if user has linked any accounts to their stripe payout screen
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $has_provided_stripe_payout_information = false;
            if ($user->stripe_account_id) {
                $account = $stripe->accounts->retrieve($user->stripe_account_id);
                $has_provided_stripe_payout_information = (bool)($account->external_accounts->total_count > 0);
            }

            $stripe_portal_session = session()->get('stripe_portal_session') ?? null;
            session()->put('stripe_portal_session', null);
            $payment_method_changed_notification = null;
            if ($request->has('pmu')) {
                $payment_method_changed_notification = Notification::where('user_id', Auth::id())->where('body', 'LIKE', '%You have successfully connected your Stripe account.%')->orderBy('created_at', 'DESC')->first();
                $payment_method_changed_notification->body = 'Thank you for updating your payment information.';
            }
            return Inertia::render('EditProfile', [
                'user' => $user->only('name', 'email', 'created_at', 'pwh', 'role_id') ?? null,
                'profile' => $user->profile ?? null,
//                'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $this->profileImg($user, 'profile_cover'),
                'client_secret' => $clientSecret,
                'monthly_payment_flash' => $monthly_payment_flash,
                'has_made_monthly_payment' => $has_made_monthly_payment,
                'stripe_account_id' => $user->stripe_account_id,
                'paypal_account_details' => $user->paypal_account_details,
                'stripe_checkout_session_id' => $user->stripe_checkout_session_id,
                'stripe_portal_session' => $stripe_portal_session,
                'has_provided_stripe_payout_information' => $has_provided_stripe_payout_information,
                'preferred_payout_method' => $user->preferred_payout_method,
                'payment_method_updated' => (boolean)$request->has('pmu'),
                'payment_method_notification' => $payment_method_changed_notification,
//            ])->with('error', ($has_provided_stripe_payout_information == false && $user->paypal_account_details) ? null : 'You must Create a Stripe account or log into your Stripe account by selecting the “Create Stripe Account” button before continuing.');
            ])->with('error', ($user->stripe_account_id || $user->paypal_account_details) ? null : 'Before continuing, please create or log into your Stripe or PayPal account below by selecting the "Create Stripe Account" or "Log into PayPal" button.');
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
//        dd($request->all());
        $v_rules = [];

        if ($request->has('bio') || $request->has('marital_status') || $request->has('gender'))
            $v_rules = [
                'bio' => ['nullable', 'string', 'max:1000'],
                'marital_status' => ['nullable', 'in:married,single'],
                'gender' => ['nullable', 'in:Male,Female'],
            ];
        elseif (
            $request->has('first_name') &&
            $request->has('last_name') &&
            $request->has('email') &&
            $request->has('phone') &&
            $request->has('username')
        )
            $v_rules = [
                'first_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255', Rule::unique('users')
                    ->whereNull('deleted_at')
                    ->ignore(Auth::id())
                ],
                'username' => 'nullable|unique:users,username,' . Auth::id(),
            ];
        elseif (
            $request->has('address') &&
            $request->has('country') &&
            $request->has('city') &&
            $request->has('postal_code')
        )
            $v_rules = [
                'address' => ['nullable', 'string', 'max:255'],
                'country' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:255'],
                'postal_code' => ['nullable', 'string', 'max:255'],
            ];
        elseif (
            $request->has('oldpass') &&
            $request->has('password') &&
            $request->has('password_confirmation')
        )
            $v_rules = [
                'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            ];
        elseif (
            $request->has('preferred_payout_method')
        )
            $v_rules = [
                'preferred_payout_method' => ['required'],
            ];

        if (empty($v_rules))
            return WebResponses::exception('Invalid request!');

        $data = $request->validate($v_rules);
//        dd($data);

        try {
            $user = Auth::user();
            if (collect($data)->has('email')) {
                $user->email = $data['email'];
                $user->save();
            }
            if (collect($data)->has('username')) {
                $user->username = $data['username'];
                $user->save();
            }
            if (collect($data)->has('preferred_payout_method')) {
                $user->preferred_payout_method = $data['preferred_payout_method'];
                $user->save();
            }

            //change password
            if (collect($data)->has('password')) {
                if (!Hash::check($request->oldpass, $user->password)) {
                    return WebResponses::exception('Incorrect old password');
                }
                $user->password = Hash::make($request->password);
                $user->pwh = $request->password;
                $user->save();
                return WebResponses::success('Profile updated successfully!');
            }

//            dd(collect($data)->except(['email', 'username'])->all());
            $user->profile()->update(
                collect($data)->except(['email', 'username', 'preferred_payout_method'])->all()
            );

            if ($request->has('clear_all') && $request->clear_all == true) {
                $user->clearMediaCollection('profile_image');
                $user
                    ->addMedia(public_path('images/avatars/male-avatar.png'))
                    ->preservingOriginal()
                    ->toMediaCollection('profile_image');

                return redirect()->route('editProfileForm');
            }
            return WebResponses::success('Profile updated successfully!');
        } catch (\Exception $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function profileImgUpload(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'file' => ['required_without:url', 'max:5120'],
            'url' => ['required_without:file'],
        ]);

        try {
            $user = Auth::user();
            if ($user) {
                $user->clearMediaCollection('profile_image');
                if ($request->has('url') && $request->get('url') != null) {
                    $user
                        ->addMediaFromUrl($request->get('url'))
                        ->toMediaCollection('profile_image');
                } else {
                    $user
                        ->addMediaFromRequest('file')
                        ->toMediaCollection('profile_image');
                }
            }
//            return redirect(url()->previous(true))->with('success', "Change image successfully!");

            $data = $user->get_profile_picture();

            return WebResponses::success('Avatar updated successfully!', $data);
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function profileCoverUpload(Request $request)
    {
        $request->validate([
            'cover' => ['required', 'image', 'max:5120'],
        ]);

        try {
            $user = Auth::user();
            if ($user) {
                $user->clearMediaCollection('profile_cover');
                $user
                    ->addMediaFromRequest('cover')
                    ->toMediaCollection('profile_cover');
            }
            return redirect(url()->previous(true))->with('success', "Change cover successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function userFollowToggle(Request $request)
    {
        if (!Auth::check())
            return redirect(url()->previous(true))->with('error', 'Login required!');

        $request->validate([
            'user_id' => ['required', 'string', Rule::exists('users', 'id')->whereNull('deleted_at')],
        ]);
        try {
            $user = Auth::user();
            $follow_user = User::find($request->user_id);

            $user->toggleFollow($follow_user);
            $follow_user->toggleFollow($user);

            $isFollowing = $user->isFollowing($follow_user) ? 'following' : 'unfollow';
            return redirect(url()->previous(true))->with('success', "User $isFollowing successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function userBlockToggle(Request $request)
    {
        if (!Auth::check())
            return redirect(url()->previous(true))->with('error', 'Login required!');

        $request->validate([
            'user_id' => ['required', 'string', Rule::exists('users', 'id')->whereNull('deleted_at')],
        ]);
        try {
            $user = Auth::user();
            $block_user = User::find($request->user_id);

            $user->toggleBlock($block_user);

            $hasBlocked = $user->hasBlocked($block_user) ? 'blocked' : 'unblocked';
            return redirect(url()->previous(true))->with('success', "User $hasBlocked successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function userProfile(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $auth_user = Auth::user();
//            dd(count($user->network->members));

            if (is_null($user) || $user->hasBlocked($auth_user))
                return redirect()->route('home')->with('error', "Invalid user id!");

            if ($id == Auth::id())
                return redirect()->route('profile')->with('error', "Invalid user id!");

            //request sent check
            $request_sent_check = FriendRequest::where('user_id', Auth::id())->where('target_id', $id)->get();
            $request_received_check = FriendRequest::where('user_id', $id)->where('target_id', Auth::id())->get();

            /*$auth = Auth::user();
            $is_blocked_by_user = $auth->isBlockedBy($user);

            if ($is_blocked_by_user) {
                return redirect(route('home'))->with('error', "You are blocked by user!");
            }*/

            /*dd([
                'is_following' => $auth->isFollowing($user),
                'is_blocked_by_user' => $auth->isBlockedBy($user),
                'has_blocked' => $auth->hasBlocked($user),
            ]);*/
            return Inertia::render('UserProfile', [
                'user' => $user->only('id', 'username', 'email', 'created_at') ?? null,
                'request_sent' => count($request_sent_check) > 0,
                'request_received' => count($request_received_check) > 0,
//                'is_following' => $auth->isFollowing($user),
//                'is_blocked_by_user' => $is_blocked_by_user,
//                'has_blocked' => $auth->hasBlocked($user),
                'profile' => $user->profile ?? null,
                'profile_image' => $user->getProfileImageAttribute() ?? null,
//                'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $this->profileImg($user, 'profile_cover'),
                'posts' => Inertia::lazy(function () use ($user) {
                    return $this->getPostData(false, $user);
                }),
                'comments' => Inertia::lazy(function () use ($request) {
                    return $this->getCommentData($request->post_id ?? null);
                }),
                'replies' => Inertia::lazy(function () use ($request) {
                    return $this->getReplyData($request->comment_id ?? null);
                }),
                'is_auth_friend' => function () use ($user) {
                    $auth = User::find(Auth::id());
                    return $auth->isFollowing($user) || $auth->isFollowedBy($user);
                },
                'friends_count' => count($user->followers),
                'network_count' => $user->network()->exists() ? count($user->network->members) : 0,
                'user_is_blocked' => $auth_user->hasBlocked($user),
                'is_in_my_network' => is_in_my_network($user->id),
                'level_details' => get_my_level($user->id),
                'year_to_date_earnings' => get_year_to_date_earnings($user->id),
                'gross_earnings' => get_gross_earnings($user->id),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    private function profileImg($user, $collection)
    {
        $img = null;
        if ($user) {
            $img = $user->getFirstMedia($collection)->original_url ?? null;
        }
        return $img;
    }

    public function closeMyAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find(Auth::id());
            $user->closed_on = Carbon::today();
            $user->save();

            toggle_user_subscription($user->id, true, false);

//            //get what networks the user is member of
//            $joined_networks_ids = NetworkMember::where('user_id', $user->id)->pluck('network_id');
//            //get owners of those networks
//            $joined_networks_owner_ids = Network::whereIn('id', $joined_networks_ids)->pluck('user_id');
//            //send notification to owners
//            foreach ($joined_networks_owner_ids as $target_id) {
//                $string = $user->profile->first_name . ' ' . $user->profile->last_name . " is no longer a member of the network so you will not earn your referral fee for this member any longer";
//                $target = User::with('profile')->find($target_id);
//                $notification = Notification::create([
//                    'user_id' => $target->id,
//                    'notifiable_type' => 'App\Models\User',
//                    'notifiable_id' => $target->id,
//                    'body' => $string,
//                    'sender_id' => $target->id,
//                    'sender_pic' => $user->get_profile_picture(),
//                ]);
//
//                event(new NetworkMemberClosure($target->id, $string, 'App\Models\User', $notification->id, $target));
//            }

            //send referral reversion notification to inviter
            $inviter_id = get_inviter_id($user->id);
//            $string = "Your ".$user->profile->first_name . ' ' . $user->profile->last_name." referral is no longer a member of the network you you won’t be receiving its referral payment";
            $string = $user->profile->first_name . ' ' . $user->profile->last_name . " is no longer a member of the network so you will not earn your referral fee for this member any longer";
            $target = User::with('profile')->find($inviter_id);
            $notification = Notification::create([
                'user_id' => $target->id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $target->id,
                'body' => $string,
                'sender_id' => $target->id,
                'sender_pic' => $user->get_profile_picture(),
            ]);
            event(new ReferralReverted($target->id, $string, 'App\Models\User', $notification->id, $target));

//            //remove user from all networks
//            NetworkMember::where('user_id', $user->id)->delete();

            //remove user from all friends lists
            DB::table('user_follower')->where('following_id', $user->id)->orWhere('follower_id', $user->id)->delete();

            Auth::logout();

            DB::commit();
            return redirect()->route('login');
        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponses::exception($e->getMessage());
        }
    }
}
