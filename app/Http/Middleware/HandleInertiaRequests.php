<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Middleware;
use App\Traits\NotificationData;

class HandleInertiaRequests extends Middleware
{
    use UserData, NotificationData;
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $share_data = [
//            'auth' => auth()->check() ? auth()->user()->only('id', 'username', 'email') : null,
            'auth' => auth()->check() ? User::with('profile')->select('id', 'username', 'email')->find(auth()->user()->id) : null,
            'role_id' => Auth::user()->role_id ?? null,
            'auth_profile_image' => auth()->check() && auth()->user()->getFirstMediaUrl('profile_image') ? auth()->user()->getFirstMediaUrl('profile_image') : asset('images/char-usr.png'),
            'flash' => [
                'success' => function () use ($request) {
                    return $request->session()->get('success');
                },
                'error' => function () use ($request) {
                    return $request->session()->get('error');
                }
            ],
            'peoples' => Inertia::lazy(function () use ($request) {
                return $this->getPeoplesData($request);
            }),
            'new_members' => Inertia::lazy(function () use ($request) {
                return $this->getNewMembersData($request);
            }),
            'friends' => Inertia::lazy(function () use ($request) {
                return $this->getFriendsData($request);
            }),
            'all_users' => Inertia::lazy(function () use ($request) {
                return $this->getAllUsersData($request);
            }),
            'network_members' => Inertia::lazy(function () use ($request) {
                return $this->getNetworkMemberssData($request);
            }),
            'blocked_users' => Inertia::lazy(function () use ($request) {
                return $this->getBlockedUsersData($request);
            }),
            'friend_requests' => Inertia::lazy(function () use ($request) {
                return $this->getFriendRequestsData($request);
            }),
            'notification_count' => Inertia::lazy(function () use ($request) {
                return $this->totalNotificationCount();
            }),
            'unread_notifications' => Inertia::lazy(function () use ($request) {
                return $this->unreadNotifications();
            }),
            'read_notifications' => Inertia::lazy(function () use ($request) {
                return $this->readNotifications();
            }),
        ];

        if ($request->session()->has('v_data')) {
            $share_data['v_data'] = $request->session()->get('v_data');
        }

        return array_merge(parent::share($request), $share_data);
    }
}
