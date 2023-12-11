<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function channels(Request $request)
    {
        try {
            $users = User::select('id', 'email', 'username', 'role_id')->where('id', '!=', auth('api')->id())->where('role_id', 2)
                ->whereHas('channels', function ($q) use ($request) {
                    return $q->where('creator_id', auth('api')->id())->orWhere('participants', 'LIKE', auth('api')->id());
                })
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
                })
                ->latest()
                ->simplePaginate(10)
                ->through(function ($item, $key) {
                    $channel_id = get_channel_id(auth('api')->id(), $item->id);
                    $item = get_user_profile($item->id, false);
                    $item['channel_id'] = $channel_id;

                    return $item;
                });

            return response()->json(array_merge([ 'success' => true ], $users->toArray()), 200);
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

    public function messages(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            if (!$channel = Channel::where('id', $request->channel_id)->where('participants', 'LIKE', '%'.auth('api')->id().'%')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Channel not found',
                    'data' => [],
                    'errors' => [],
                ], 401);
            }

            $messages = $channel
                ->messages()->orderBy('created_at', 'DESC')
                ->select('id', 'content', 'sender_id', 'created_at', 'channel_id')
                ->with(['sender' => function ($q) {
                    $q->select('id', 'username');
                }])
                ->whereDoesntHave('userDelete', function ($q) {
                    $q->where('user_id', auth('api')->id());
                })->when($userDelete = $channel->userDelete()->where('user_id', auth('api')->id())->first(), function ($q) use ($userDelete) {
                    return $q->where('created_at', '>', $userDelete->created_at);
                })->latest()
                ->simplePaginate(10)
                ->through(function ($item, $key) {
                    $item->sender = get_user_profile($item->sender_id, false);
                    return $item;
                });

            return response()->json(array_merge([ 'success' => true ], $messages->toArray()), 200);
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
