<?php

namespace App\Traits;

use App\Models\User;
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
            ->latest()
            ->simplePaginate(5)
            ->through(function ($item, $key) {
                $item->auth_id = Auth::id();
                $item->profile_img = $item->getFirstMediaUrl('profile_image') ?? null;
                return $item;
            });
    }
}
