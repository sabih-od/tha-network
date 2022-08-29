<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function sendRequest(Request $request, $target_id) {
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if($auth->isFollowing($target) || $auth->isFollowedBy($target)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'User already in friend list.');
            }

            $check = FriendRequest::where('user_id', Auth::id())->where('target_id', $target_id)->get();
            if(count($check) > 0) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Request Already Sent');
            }

            FriendRequest::create([
                'user_id' => Auth::id(),
                'target_id' => $target_id,
            ]);
            return redirect()->route('userProfile', $target_id)->with('success', 'Request Sent Successfully!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }

    public function acceptRequest(Request $request, $target_id) {
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if($auth->isFollowing($target) || $auth->isFollowedBy($target)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'User already in friend list.');
            }

            $check = FriendRequest::where('user_id', $target_id)->where('target_id', Auth::id())->get();
            if(count($check) == 0) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Request not found.');
            }

            $auth->follow($target);
            $target->follow($auth);
            $check[0]->delete();
            return redirect()->route('userProfile', $target_id)->with('success', 'Added to friend list!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }

    public function rejectRequest(Request $request, $target_id) {
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            $check = FriendRequest::where('user_id', $target_id)->where('target_id', Auth::id())->get();
            if(count($check) == 0) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Request not found.');
            }
            $check[0]->delete();
            return redirect()->route('userProfile', $target_id)->with('success', 'Request Rejected!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }

    public function unfriend(Request $request, $target_id) {
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if(!$auth->isFollowing($target) || !$auth->isFollowedBy($target)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'User not in friend list.');
            }

            $auth->unfollow($target);
            $target->unfollow($auth);

            return redirect()->route('userProfile', $target_id)->with('success', 'Removed from friend list!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }

    public function block(Request $request, $target_id) {
//        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if($auth->hasBlocked($target) || $target->hasBlocked($auth)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Already blocked.');
            }

            $auth->block($target);
            $target->block($auth);

            return redirect()->route('home')->with('success', 'Blocked user successfully!');
//        } catch (\Exception $e) {
//            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
//        }
    }
}
