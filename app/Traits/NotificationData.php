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
        ])->count();
    }

    protected function unreadNotifications()
    {
        return Notification::with('sender.profile')->where([
            ['user_id', Auth::id()],
            ['viewed', 0]
        ])->orderBy('created_at', 'DESC')->get();
    }

    protected function readNotifications()
    {
        return Notification::with('sender.profile')->where([
            ['user_id', Auth::id()],
            ['viewed', 1]
        ])->limit(8)->orderBy('created_at', 'DESC')->get();
    }
}
