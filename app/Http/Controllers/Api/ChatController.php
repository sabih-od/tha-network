<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $item = get_user_profile($item->id);
                    $item->channel_id = get_channel_id(auth('api')->id(), $item->id);
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
}
