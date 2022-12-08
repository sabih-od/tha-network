<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestAccepted;
use App\Events\FriendRequestReceived;
use App\Helpers\WebResponses;
use App\Models\FriendRequest;
use App\Models\Notification;
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
//                return WebResponses::exception('User already in friend list.');
            }

            $check = FriendRequest::where('user_id', Auth::id())->where('target_id', $target_id)->get();
            $check2 = FriendRequest::where('user_id', $target_id)->where('target_id', Auth::id())->get();

            if (count($check) > 0) {
                $check[0]->delete();
            }
            if (count($check2) > 0) {
                $check2[0]->delete();
            }
            if(count($check) > 0 || count($check2) > 0) {
                return redirect()->route('userProfile', $target_id)->with('success', 'Request Cancelled Successfully');
//                return redirect()->route('userProfile', $target_id)->with('error', 'Request Already Sent');
            }

            FriendRequest::create([
                'user_id' => Auth::id(),
                'target_id' => $target_id,
            ]);

            //friend request notification
            $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " has sent you a friend request.";
            $notification = Notification::create([
                'user_id' => $target_id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $target_id,
                'body' => $string,
                'sender_id' => $auth->id
            ]);
            event(new FriendRequestReceived($target_id, $string, 'App\Models\User', $notification->id, $target));

            if($request->has('redirect')) {
                return WebResponses::success('Request Sent Successfully!');
            }
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

            //friend request notification
            $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " has accepted your friend request.";
            $notification = Notification::create([
                'user_id' => $target_id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $target_id,
                'body' => $string,
                'sender_id' => $auth->id
            ]);
            event(new FriendRequestAccepted($target_id, $string, 'App\Models\User', $notification->id, $target));

            if($request->has('redirect')) {
                return back()->with('success', 'Added to friend list!');
            }
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

            if($request->has('redirect')) {
                return back()->with('success', 'Request Rejected!');
            }
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
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if($auth->hasBlocked($target)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Already blocked.');
            }

            $auth->unfollow($target);
            $target->unfollow($auth);
            $auth->block($target);

//            return redirect()->route('home')->with('success', 'Blocked user successfully!');
            return redirect()->route('userProfile', $target_id)->with('success', 'Blocked user successfully!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }

    public function unblock(Request $request, $target_id) {
        try {
            $auth = User::find(Auth::id());
            $target = User::find($target_id);

            if(!$auth->hasBlocked($target)) {
                return redirect()->route('userProfile', $target_id)->with('error', 'Already unblocked.');
            }

            $auth->unblock($target);

            return redirect()->route('userProfile', $target_id)->with('success', 'Unblocked user successfully!');
        } catch (\Exception $e) {
            return redirect()->route('userProfile', $target_id)->with('error', $e->getMessage());
        }
    }
}
