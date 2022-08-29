<?php

namespace App\Http\Controllers;

use App\Helpers\ProfileUtils;
use App\Models\Channel;
use App\Models\User;
use App\Models\UserDelete;
use App\Traits\ChannelData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChannelController extends Controller
{
    use ChannelData;

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->whereNull('deleted_at')
            ],
            'chat_type' => [
                'required',
                'string',
                'in:group,individual'
            ]
        ]);

        try {
            $auth = Auth::user();
            $user = User::find($request->user_id);

            if (!$auth->isFollowing($user) && !$auth->isFollowedBy($user))
                return redirect()->route('chatIndex')->with('error', 'You are not following this user!');

            if ($user->hasBlocked($auth) || $auth->hasBlocked($user))
                return redirect()->route('chatIndex')->with('error', 'You do not have enough permissions to send message to this user.');

//            if ($auth->isBlockedBy($user))
//                return redirect()->route('chatIndex')->with('error', 'You are blocked by this user!');

            $channel = Channel::where(function ($q) use ($auth, $user) {
                return $q->whereRaw("participants = CAST('" . json_encode([$auth->id, $user->id]) . "' AS JSON)")
                    ->orWhereRaw("participants = CAST('" . json_encode([$user->id, $auth->id]) . "' AS JSON)");
            })->where('chat_type', $request->chat_type)->first();

            if (is_null($channel)) {
                $channel = new Channel;
                $channel->creator_id = $auth->id;
                $channel->users()->attach([$auth->id, $user->id]);
                $channel->save();
            }

            $cover_data = null;
            if ($channel->chat_type == 'individual') {
                $cover_data = $channel->users()
                    ->select('id', 'username')
                    ->where('id', '<>', Auth::id())
                    ->first();

                $cover_data->profile_img = ProfileUtils::profileImg($cover_data, 'profile_image');
            }/*elseif ($item->chat_type == 'group') {
                $cover_data = $item->group()
                    ->select('id', 'name')
                    ->first();

                $cover_data->profile_img = $cover_data->getFirstMedia('group_media')->original_url ?? null;
            }*/

            unset(
                $channel->updated_at,
                $channel->deleted_at,
                $channel->participants,
                $channel->chat_type,
            );

            return redirect()->route('chatIndex')
                ->with('success', 'Conversation created successfully!')
                ->with('v_data', [
                    'channel' => $channel,
                    'cover_data' => $cover_data
                ]);
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, $channel_id)
    {
        try {
            $channel = $this->getChannelDetails($request, $channel_id);

            return response()->json($channel);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function channelDestroy(Request $request)
    {
        $channel = null;
        $request->validate([
            'id' => [
                'required',
                function ($attribute, $value, $fail) use (&$channel) {
                    if (!$value) return;

                    $channel = Channel::where([
                        ['id', $value]
//                    ])->whereDoesntHave('userDelete')->first();
                    ])->first();

                    if (is_null($channel)) {
                        $fail("Invalid channel!");
                    }
                }
            ]
        ]);
        try {
            if($channel->userDelete()->exists()) {
                $user_delete = UserDelete::find($channel->userDelete->id);
                $user_delete->updated_at = Carbon::now();
                $user_delete->save();
            } else {
                $channel->userDelete()->create([
                    'user_id' => Auth::id()
                ]);
            }

            return redirect()->route('chatIndex')->with('success', 'Channel deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }
}
