<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait UserData
{
    protected function getPeoplesData(Request $request)
    {
        $query = User::select('id', 'email', 'username');

        if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        return $query
            ->with('profile')
            ->latest()
            ->simplePaginate(5)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                return $item;
            });
    }

    protected function getNewMembersData(Request $request)
    {
        $start_of_week = Carbon::now()->startOfWeek();
        $query = User::select('id', 'email', 'username');

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
            ->where('id', '!=', Auth::id())
            ->where('created_at', '>=', $start_of_week)
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(8)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find(Auth::id()));
                return $item;
            });
    }

    protected function getFriendsData(Request $request)
    {
        $query = User::select('id', 'email', 'username');

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
            ->where('id', '!=', Auth::id())
            ->simplePaginate(8)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                $item->is_followed = $item->isFollowedBy(User::find(Auth::id()));
                return $item;
            });
    }
}
