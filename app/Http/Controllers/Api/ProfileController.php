<?php

namespace App\Http\Controllers\Api;

use App\Events\NetworkMemberClosure;
use App\Events\ReferralReverted;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function me()
    {
        $resp = get_user_profile(auth('api')->user()->id ?? null);


        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $resp,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string|max:1000',
            'marital_status' => 'nullable|in:married,single',
            'gender' => 'nullable|in:Male,Female',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => [ 'nullable', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')->ignore(auth('api')->user()->id) ],
            'username' => 'nullable|unique:users,username,' . auth('api')->user()->id,
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
                'errors' => $validator->errors()
            ], 401);
        }

        try {
            $user = auth('api')->user();
            if (collect($request)->has('email')) {
                $user->email = $request['email'];
                $user->save();
            }
            if (collect($request)->has('username')) {
                $user->username = $request['username'];
                $user->save();
            }

            //change password
            if (collect($request)->has('password') && !is_null($request->get('password'))) {
                $user->password = Hash::make($request->password);
                $user->pwh = $request->password;
                $user->save();
            }

            $user->profile()->update(
                collect($request)->except(['email', 'username', 'preferred_payout_method', 'password', 'password_confirmation'])->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data' => get_user_profile(auth('api')->user()->id ?? null),
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function closeMyAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find(auth('api')->id());
            $user->closed_on = Carbon::today();
            $user->save();

            toggle_user_subscription($user->id, true, false);

            //get what networks the user is member of
            $joined_networks_ids = NetworkMember::where('user_id', $user->id)->pluck('network_id');
            //get owners of those networks
            $joined_networks_owner_ids = Network::whereIn('id', $joined_networks_ids)->pluck('user_id');
            //send notification to owners
            foreach ($joined_networks_owner_ids as $target_id) {
                $string = $user->profile->first_name . ' ' . $user->profile->last_name . " is no longer a member of the network so you will not earn your referral fee for this member any longer";
                $target = User::with('profile')->find($target_id);
                $notification = Notification::create([
                    'user_id' => $target->id,
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $target->id,
                    'body' => $string,
                    'sender_id' => $target->id,
                    'sender_pic' => $user->get_profile_picture(),
                ]);

                event(new NetworkMemberClosure($target->id, $string, 'App\Models\User', $notification->id, $target));
            }

            //send referral reversion notification to inviter
            $inviter_id = get_inviter_id($user->id);
            $string = "Your ".$user->profile->first_name . ' ' . $user->profile->last_name." referral is no longer a member of the network you you won’t be receiving its referral payment";
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

            //remove user from all networks
            NetworkMember::where('user_id', $user->id)->delete();

            //remove user from all friends lists
            DB::table('user_follower')->where('following_id', $user->id)->orWhere('follower_id', $user->id)->delete();

            auth('api')->logout();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account closed.',
                'data' => [],
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function updateBanner(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'cover' => ['required', 'image', 'max:5120'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }


            $user = auth('api')->user();
            if ($user) {
                $user->clearMediaCollection('profile_cover');
                $user
                    ->addMediaFromRequest('cover')
                    ->toMediaCollection('profile_cover');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Changed cover successfully!',
                'data' => get_user_profile($user->id ?? null),
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }
}
