<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{

    public function show(Request $request)
    {
        try {
            $user = Auth::user();
            return Inertia::render('Profile', [
                'user' => $user->only('id', 'name', 'email', 'created_at') ?? null,
                'profile' => $user->profile ?? null,
                /*'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $this->profileImg($user, 'profile_cover'),
                'posts' => Inertia::lazy(function () {
                    return $this->getPostData(true);
                }),
                'comments' => Inertia::lazy(function () use ($request) {
                    return $this->getCommentData($request->post_id ?? null);
                }),
                'replies' => Inertia::lazy(function () use ($request) {
                    return $this->getReplyData($request->comment_id ?? null);
                }),*/
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
//                'profile_cover' => $this->profileImg($user, 'profile_cover')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $v_rules = [];

        if ($request->has('bio'))
            $v_rules = [
                'bio' => ['required', 'string', 'max:1000'],
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

        if (empty($v_rules))
            return WebResponses::exception('Invalid request!');

        $data = $request->validate($v_rules);

        try {
            $user = Auth::user();
            if (collect($data)->has('email')) {
                $user->email = $data['email'];
                $user->save();
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
            'file' => ['required', 'image', 'max:5120'],
        ]);

        try {
            $user = Auth::user();
            if ($user) {
                $user->clearMediaCollection('profile_image');
                $user
                    ->addMediaFromRequest('file')
                    ->toMediaCollection('profile_image');
            }
            return redirect(url()->previous(true))->with('success', "Change image successfully!");
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
            if (is_null($user))
                return redirect()->route('home')->with('error', "Invalid user id!");

            $auth = Auth::user();
            $is_blocked_by_user = $auth->isBlockedBy($user);

            if ($is_blocked_by_user) {
                return redirect(route('home'))->with('error', "You are blocked by user!");
            }

            /*dd([
                'is_following' => $auth->isFollowing($user),
                'is_blocked_by_user' => $auth->isBlockedBy($user),
                'has_blocked' => $auth->hasBlocked($user),
            ]);*/

            return Inertia::render('UserProfile', [
                'user' => $user->only('id', 'name', 'email', 'created_at') ?? null,
                'is_following' => $auth->isFollowing($user),
                'is_blocked_by_user' => $is_blocked_by_user,
                'has_blocked' => $auth->hasBlocked($user),
                'profile' => $user->profile ?? null,
                'profile_image' => $this->profileImg($user, 'profile_image'),
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
