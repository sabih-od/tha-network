<?php

namespace App\Traits;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ChannelData
{
    protected function getChannelDetails(Request $request, $channel_id)
    {
        $channel = Channel::where([
            ['id', $channel_id],
            ['chat_type', 'group'],
        ])->whereHas('users', function ($q) {
            $q->where('id', Auth::id());
        })->with([
            'users',
            'group.creator',
            'group.moderatorsUser',
        ])->first();

        if (!empty($channel)) {
            $channel = $channel->toArray();
            $channel['users'] = collect($channel['users'])->filter(function ($user) use ($channel) {
                return $user['id'] != $channel['group']['creator']['id'] &&
                    !in_array($user['id'], ($channel['group']['moderators'] ?? []));
            })->values();

            unset(
                $channel['creator_id'],
                $channel['chat_type'],
                $channel['participants'],
                $channel['group']['creator_id'],
            );
        }

        return $channel;

        /*if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }*/


    }
}
