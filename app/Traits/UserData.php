<?php

namespace App\Traits;

use App\Models\FriendRequest;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

trait UserData
{
    protected function getFollowsData(Request $request, $user_id = null, $limit = 10)
    {
        $user_id = $user_id ?? Auth::id();
        $query = User::select('id', 'email', 'username')->where('role_id', 2)
            ->where(function ($q1) use ($user_id) {
                $q1->whereHas('followings', function ($q) use ($user_id) {
                    $q->where('following_id', $user_id);
                })->orWhereHas('followers', function ($q) use ($user_id) {
                    $q->where('follower_id', $user_id);
                });
            })
            ->where('id', '<>', $user_id);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->simplePaginate($limit)
            ->through(function ($item, $key) use ($user_id) {
                $auth_user = Auth::user();
//                $item->auth_id = $auth_user->id;
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->is_following = $auth_user->isFollowing($item);
                $item->is_follower = $auth_user->isFollowedBy($item);
                $item->profile_img = $item->getFirstMedia('profile_image')->original_url ?? null;

                /*// add media in item
                $item->getMedia('post_upload');
                $files = [];
                foreach ($item->media as $media) {
                    $files[] = [
                        'mime_type' => $media->mime_type,
                        'url' => $media->original_url,
                    ];
                }
                $item->media_items = $files;

                // add profile image in item
                if ($item->user) {
                    $item->user->profile_img = $item->user->getFirstMedia('profile_image')->original_url ?? null;

                    // follow user
                    if ($item->user->id != $user->id) {
                        $item->user->is_followed = $user->isFollowing($item->user);
                    }
                }

                $user->attachLikeStatus($item);

                $likers = $item->likers()->latest()->simplePaginate(2);
                $r_likers = [];
                foreach ($likers as $user) {
                    $r_likers[] = $user->only('id', 'name');
                }
                $item->recent_likes = $r_likers;

                // share post data
                if ($item->sharedPost) {
                    $item->sharedPost->getMedia('post_upload');
                    $s_files = [];
                    foreach ($item->sharedPost->media as $media) {
                        $s_files[] = [
                            'mime_type' => $media->mime_type,
                            'url' => $media->original_url,
                        ];
                    }
                    $item->sharedPost->media_items = $s_files;
                    // add profile image in item
                    if ($item->sharedPost->user) {
                        unset(
                            $item->sharedPost->user->created_at,
                            $item->sharedPost->user->deleted_at,
                            $item->sharedPost->user->email_verified_at,
                            $item->sharedPost->user->updated_at
                        );
                        $item->sharedPost->user->profile_img = $item->sharedPost->user->getFirstMedia('profile_image')->original_url ?? null;
                        // follow user
                        if ($item->sharedPost->user->id != $user->id) {
                            $item->sharedPost->user->is_followed = $user->isFollowing($item->sharedPost->user);
                        }
                    }
                }*/
                return $item;
            });
    }

    protected function getPeoplesData(Request $request)
    {
        $query = User::select('id', 'email', 'username', 'role_id')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->where('role_id', 2)
            ->latest()
            ->simplePaginate(5)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $auth_user = Auth::user();
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                return $item;
            });
    }

    protected function getNewMembersData(Request $request)
    {
        $start_of_week = Carbon::now()->startOfWeek();
        $query = User::select('id', 'email', 'username')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->where('created_at', '>=', $start_of_week)
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(8)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $auth_user = Auth::user();
                $request_sent_check = FriendRequest::where('user_id', Auth::id())->where('target_id', $item->id)->get();
                $request_received_check = FriendRequest::where('user_id', $item->id)->where('target_id', Auth::id())->get();
                $item->request_sent = count($request_sent_check) > 0;
                $item->request_received = count($request_received_check) > 0;
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find(Auth::id()));
                $item->is_followed_by_auth = $item->isFollowedBy(User::find($auth_user->id));
                return $item;
            });
    }

    protected function getFriendsData(Request $request)
    {
        $user_id = $request->has('user_id') ? $request->get('user_id') : Auth::id();
        $query = User::select('id', 'email', 'username')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->where('id', '!=', $user_id)
            ->get()
            ->map(function ($item, $key) use($user_id) {
                $item->auth_id = $user_id;
                $auth_user = Auth::user();
                $request_sent_check = FriendRequest::where('user_id', Auth::id())->where('target_id', $item->id)->get();
                $request_received_check = FriendRequest::where('user_id', $item->id)->where('target_id', Auth::id())->get();
                $item->request_sent = count($request_sent_check) > 0;
                $item->request_received = count($request_received_check) > 0;
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find($user_id));
                $item->is_followed_by_auth = $item->isFollowedBy(User::find($auth_user->id));
                return $item;
            });
    }

    protected function getNetworkMemberssData(Request $request)
    {
        $user_id = $request->has('user_id') ? $request->get('user_id') : Auth::id();
        $network = Network::where('user_id', $user_id)->first();
        $network_members = $network->members()->get();
        $network_member_ids = [];
        foreach ($network_members as $network_member) {
            array_push($network_member_ids, $network_member->user_id);
        }
        $query = User::select('id', 'email', 'username')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->whereIn('id', $network_member_ids)
            ->where('id', '!=', $user_id)
            ->simplePaginate($request->all == "true" ? 99999999 : 8)
            ->through(function ($item, $key) use($user_id) {
                $item->auth_id = $user_id;
                $auth_user = Auth::user();
                $request_sent_check = FriendRequest::where('user_id', Auth::id())->where('target_id', $item->id)->get();
                $request_received_check = FriendRequest::where('user_id', $item->id)->where('target_id', Auth::id())->get();
                $item->request_sent = count($request_sent_check) > 0;
                $item->request_received = count($request_received_check) > 0;
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find($user_id));
                $item->is_followed_by_auth = $item->isFollowedBy(User::find($auth_user->id));
                return $item;
            });
    }

    protected function getBlockedUsersData(Request $request)
    {
        $user_id = $request->has('user_id') ? $request->get('user_id') : Auth::id();

        $query = User::select('id', 'email', 'username')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->where('id', '!=', $user_id)
            ->get()
            ->map(function ($item, $key) use($user_id) {
                $item->auth_id = $user_id;
                $auth_user = Auth::user();
                $request_sent_check = FriendRequest::where('user_id', Auth::id())->where('target_id', $item->id)->get();
                $request_received_check = FriendRequest::where('user_id', $item->id)->where('target_id', Auth::id())->get();
                $item->request_sent = count($request_sent_check) > 0;
                $item->request_received = count($request_received_check) > 0;
                $item->has_blocked = $item->hasBlocked($auth_user);
                $item->is_blocked = $auth_user->hasBlocked($item);
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find($user_id));
                $item->is_followed_by_auth = $item->isFollowedBy(User::find($auth_user->id));
                return $item;
            });
    }

    protected function getFriendRequestsData(Request $request)
    {
        $friend_requests = FriendRequest::where('target_id', Auth::id())->get();
        $friend_request_user_ids = [];
        foreach ($friend_requests as $friend_request) {
            array_push($friend_request_user_ids, $friend_request->user_id);
        }
        $query = User::select('id', 'email', 'username')->where('role_id', 2);

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('profile', function($q) use ($request) {
                        return $q->where('first_name', 'like', "%{$request->search}%")
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        return $query
            ->with('profile')
            ->whereIn('id', $friend_request_user_ids)
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(8)
            ->through(function ($item, $key) {
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                return $item;
            });
    }
}
