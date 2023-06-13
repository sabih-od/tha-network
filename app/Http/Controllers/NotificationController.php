<?php

namespace App\Http\Controllers;

use App\Events\PostLiked;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function clearNotifications(Request $request)
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())->where('viewed', 0)->update([
                'viewed' => 1
            ]);

            return redirect(url()->previous(true))->with('success', "Notifications cleared.");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function deleteNotification(Request $request)
    {
        try {
            $notification = Notification::find($request->notification_id);

            $notification->delete();

            return redirect(url()->previous(true))->with('success', "Notification deleted.");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }
}
