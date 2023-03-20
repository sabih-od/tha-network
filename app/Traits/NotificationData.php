<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

trait NotificationData
{
    protected function totalNotificationCount()
    {
        return Notification::where([
            ['user_id', Auth::id()],
            ['viewed', 0]
        ])->groupBy('sender_id')->count();
    }

    protected function unreadNotifications()
    {
        $notifications = Notification::with('sender.profile')->where([
            ['user_id', Auth::id()],
            ['viewed', 0]
//        ])->orderBy('created_at', 'DESC')->groupBy('sender_id')->get();
        ])->whereHas('sender', function ($q) {
            return $q->whereNull('closed_on');
        })->orderBy('created_at', 'DESC')->get();

        $notifications = $notifications->toArray();
        $noti_arr = [];
        foreach ($notifications as $notification) {
            $notification['last_activity_readable'] = last_active($notification['sender_id']);
            array_push($noti_arr, $notification);
        }
        return $noti_arr;
    }

    protected function readNotifications()
    {
        $notifications = Notification::with('sender.profile')->where([
            ['user_id', Auth::id()],
            ['viewed', 1]
//        ])->limit(8)->orderBy('created_at', 'DESC')->groupBy('sender_id')->get();
        ])->limit(8)->orderBy('created_at', 'DESC')->get();

        $notifications = $notifications->toArray();
        $noti_arr = [];
        foreach ($notifications as $notification) {
            $notification['last_activity_readable'] = last_active($notification['sender_id']);
            array_push($noti_arr, $notification);
        }
        return $noti_arr;
    }
}
