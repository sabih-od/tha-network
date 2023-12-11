<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function all (Request $request)
    {
        try {
            $notifications = Notification::where('user_id', auth('api')->id())
                ->orderBy('created_at', 'DESC')
                ->latest()
                ->simplePaginate(10);
//                ->through(function ($item, $key) {
//                    return get_user_profile($item->id, false);
//                });

            return response()->json(array_merge([ 'success' => true ], $notifications->toArray()), 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function unreadNotificationsCount (Request $request)
    {
        try {
            $unread_notification_count = Notification::where([
                ['user_id', auth('api')->id()],
                ['viewed', 0]
//            ])->groupBy('sender_id')->count();
            ])->count();

            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $unread_notification_count,
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
}
