<?php

namespace App\Traits;

use App\Models\Channel;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait GroupData
{
    protected function getGroupChannelsData(Request $request, $user = null, $limit = 10)
    {
        $user = $user ?? Auth::user();
        $query = Channel::select('id', 'participants', 'chat_type')
            ->where([
                ['chat_type', 'group']
            ])
            ->whereHas('users', function ($q) use ($user) {
                $q->where('id', $user->id);
            })
            ->whereHas('group', function ($q) use ($request) {
                if (!empty($request->get('search')))
                    $q->where('name', 'like', "%{$request->search}%");
            })
            ->with('group');

        return $query
            ->latest()
            ->simplePaginate($limit)
            ->through(function ($item, $key) use ($user) {
                $item->group->img = $item->group->getFirstMedia('group_media')->original_url ?? null;
                $item->group->is_created_by_me = $item->group->creator_id == Auth::id();
                $item->group->is_moderator = in_array(Auth::id(), $item->group->moderators ?? []);

                unset(
                    $item->participants,
                    $item->chat_type,
                    $item->group->creator_id,
                    $item->group->created_at,
                    $item->group->channel_id
                );

                return $item;
            });
    }

    protected function getGroupMembersData(Request $request, $user_id = null)
    {
        $user_id = $user_id ?? Auth::id();
        $data = Group::select('id', 'channel_id')
            ->where('id', $request->id)
            ->with(['channel.users' => function ($q) {
                $q->select('id', 'name');
            }])->first()->toArray();

        return collect(Arr::where(Arr::get($data, 'channel.users', []), function ($value, $key) use ($user_id) {
            return $value['id'] !== $user_id;
        }))->values();
    }
}
