<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponses;
use App\Models\FriendRequest;
use App\Models\User;
use App\Traits\CommentData;
use App\Traits\PostData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    use PostData, CommentData;

    public function show(Request $request)
    {
        try {
            $user = Auth::user();
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
                'network_count' => $user->network()->exists() ? count($user->network->members) : 0
            ]);
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->with('error', $e->getMessage());
        }
    }

    public function edit()
    {
        try {
            $user = Auth::user();
            return Inertia::render('EditProfile', [
                'user' => $user->only('name', 'email', 'created_at') ?? null,
                'profile' => $user->profile ?? null,
//                'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $this->profileImg($user, 'profile_cover')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $v_rules = [];

        if ($request->has('bio') || $request->has('marital_status'))
            $v_rules = [
                'bio' => ['required', 'string', 'max:1000'],
                'marital_status' => ['required', 'in:married,single'],
            ];
        elseif (
            $request->has('first_name') &&
            $request->has('last_name') &&
            $request->has('email') &&
            $request->has('phone')
        )
            $v_rules = [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')
                    ->whereNull('deleted_at')
                    ->ignore(Auth::id())
                ],
            ];
        elseif (
            $request->has('address') &&
            $request->has('country') &&
            $request->has('city') &&
            $request->has('postal_code')
        )
            $v_rules = [
                'address' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'postal_code' => ['required', 'string', 'max:255'],
            ];
        elseif (
            $request->has('oldpass') &&
            $request->has('password') &&
            $request->has('password_confirmation')
        )
            $v_rules = [
                'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            ];

        if (empty($v_rules))
            return WebResponses::exception('Invalid request!');

        $data = $request->validate($v_rules);

        try {
            $user = Auth::user();
            if (collect($data)->has('email')) {
                $user->email = $data['email'];
                $user->save();
            }

            //change password
            if (collect($data)->has('password')) {
                if(!Hash::check($request->oldpass, $user->password)) {
                    return WebResponses::exception('Incorrect old password');
                }
                $user->password = Hash::make($request->password);
                $user->save();
                return WebResponses::success('Profile updated successfully!');
            }

            $user->profile()->update(
                collect($data)->except(['email'])->all()
            );
            return WebResponses::success('Profile updated successfully!');
        } catch (\Exception $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function profileImgUpload(Request $request)
    {
        $request->validate([
            'file' => ['required_without:url', 'image', 'max:5120'],
            'url' => ['required_without:file'],
        ]);

        try {
            $user = Auth::user();
            if ($user) {
                $user->clearMediaCollection('profile_image');
                if($request->has('url')) {
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
            return WebResponses::success('Avatar updated successfully!');
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

        if($id == Auth::id())
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
                'is_auth_friend' => function() use ($user) {
                    $auth = User::find(Auth::id());
                    return $auth->isFollowing($user) || $auth->isFollowedBy($user);
                },
                'friends_count' => count($user->followers),
                'network_count' => $user->network()->exists() ? count($user->network->members) : 0,
                'user_is_blocked' => $auth_user->hasBlocked($user)

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
}
