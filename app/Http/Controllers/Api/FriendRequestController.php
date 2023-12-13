<?php

namespace App\Http\Controllers\Api;

use App\Events\FriendRequestAccepted;
use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FriendRequestController extends Controller
{
    public function friendRequests (Request $request)
    {
        try {
            $friend_requests = FriendRequest::where('target_id', auth('api')->id())
                ->whereHas('sender', function ($q) use ($request) {
                    return $q->where('role_id', 2)
                        ->whereNull('closed_on')
                        ->when(!is_null($request->get('search')), function ($q) use ($request) {
                            return $q->where(function ($q) use ($request) {
                                $q->where('username', 'like', "%{$request->search}%")
                                    ->orWhere('email', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('profile', function($q) use ($request) {
                                        return $q->where('first_name', 'like', "%{$request->search}%")
                                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                                    });
                            });
                        });
                })
                ->latest()
                ->simplePaginate(10)
                ->through(function ($item, $key) {
                    $item->sender = get_user_profile($item->sender_id, false);
                });

            return response()->json(array_merge([ 'success' => true ], $friend_requests->toArray()), 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function accept (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'string', Rule::exists('users', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }


            $auth = User::find(auth('api')->id());
            $target = User::find($request->user_id);

            if($auth->isFollowing($target) || $auth->isFollowedBy($target)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already in friend list.',
                    'data' => [],
                    'errors' => []
                ], 401);
            }

            $check = FriendRequest::where('user_id', $target->id)->where('target_id', $auth->id)->get();
            if(count($check) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found.',
                    'data' => [],
                    'errors' => []
                ], 401);
            }

            $auth->follow($target);
            $target->follow($auth);
            $check[0]->delete();

            //create chat channel for both
            create_chat_channel($auth->id, $target->id);

            //friend request notification
            $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " has accepted your friend request.";
            $notification = Notification::create([
                'user_id' => $target->id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $target->id,
                'body' => $string,
                'sender_id' => $auth->id,
                'sender_pic' => $auth->get_profile_picture(),
            ]);
            event(new FriendRequestAccepted($target->id, $string, 'App\Models\User', $notification->id, $target));

            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Added to friend list!',
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

    public function reject (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'string', Rule::exists('users', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $auth = User::find(auth('api')->id());
            $target = User::find($request->user_id);

            $check = FriendRequest::where('user_id', $target->id)->where('target_id', $auth->id)->get();
            if(count($check) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found.',
                    'data' => [],
                    'errors' => []
                ], 401);
            }

            $check[0]->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Request Rejected!',
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
}
