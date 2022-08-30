<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NewMessage;
use App\Models\Channel;
use App\Models\ChatMessage;
use App\Rules\isValidMedia;
use App\Traits\ChatData;
use App\Traits\UserData;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    use UserData, ChatData;

    public function index(Request $request)
    {
        try {
            return Inertia::render('Chat', [
                "users" => Inertia::lazy(function () use ($request) {
                    return $this->getFollowsData($request);
                }),
                "channels" => Inertia::lazy(function () use ($request) {
                    return $this->getChannelsData($request);
                }),
                "messages" => Inertia::lazy(function () use ($request) {
                    return $this->getMessagesData($request);
                }),
                "profile_id" => $request->has('profile_id') ? $request->get('profile_id') : null

                /*'user' => $user->only('name', 'email', 'created_at') ?? null,
                'profile' => $user->profile ?? null,
                'profile_image' => ProfileUtils::profileImg($user, 'profile_image'),
                'profile_cover' => ProfileUtils::profileImg($user, 'profile_cover'),
                'posts' => Inertia::lazy(function () {
                    return $this->getPostData(true);
                }),
                'comments' => Inertia::lazy(function () use ($request) {
                    return $this->getCommentData($request->post_id ?? null);
                }),
                'replies' => Inertia::lazy(function () use ($request) {
                    return $this->getReplyData($request->comment_id ?? null);
                }),*/
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function chatMessageStore(Request $request)
    {
        $channel = null;
        $rule = ['channel_id' => [
            'required',
            function ($attribute, $value, $fail) use (&$channel) {
                if (!$value) return;

                $channel = Channel::where('id', $value)
                    ->whereHas('users', function ($q) {
                        $q->where('id', Auth::id());
                    })->first();

                if (is_null($channel)) {
                    $fail("Invalid channel id!");
                }
            }
        ]];

        if ($request->has('file') && !is_null($request->file)) {
            $rule['file'] = [new isValidMedia];
        } else {
            $rule['message'] = ['required'];
        }

        $request->validate($rule);
        try {
            if ($channel->chat_type == 'individual') {
                $auth = Auth::user();
                $friend = $channel->users()->where('id', '<>', $auth->id)->first();
                if ($auth->isBlockedBy($friend))
                    return redirect()->route('chatIndex')->with('error', 'You are blocked by this user!');
                elseif ($auth->hasBlocked($friend))
                    return redirect()->route('chatIndex')->with('error', 'You have blocked this user!');
            }
            $message = $channel->messages()->create([
                'sender_id' => Auth::id(),
                'content' => $request->message ?? null
            ]);
            $media = null;

            if ($request->has('file') && !is_null($request->file)) {
                $message->addMediaFromRequest('file')
                    ->toMediaCollection('media');

                $f_media = $message->getFirstMedia('media');
                $media = $f_media ? [
                    'mime_type' => $f_media->mime_type,
                    'url' => $f_media->original_url,
                ] : null;
            }

            $channel->last_message_at = $message->created_at;
            $channel->save();

//            $users = $channel->users()->where('id', '<>', Auth::id())->get();

            //notification work
//            event(new NewMessage($channel->id, $message));

//            $participants = collect($channel->participants)->filter(function ($item) {
//                return $item != Auth::id();
//            })->all();
//            foreach ($participants as $user_id) {
//                $body = "You have a new message!";
//                dispatch(new CreateNotification($channel->id, 'channel', $user_id, $body));
//                event(new NewNotification($user_id, $body, 'channel', $channel->id));
//            }

            return redirect()->route('chatIndex')
                ->with('success', 'Message stored it successfully!')
                ->with('v_data', [
                    'id' => $message->id,
                    'media' => $media
                ]);
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }

    public function chatMessageDestroy(Request $request)
    {
        $message = null;
        $request->validate([
            'id' => [
                'required',
                function ($attribute, $value, $fail) use (&$message) {
                    if (!$value) return;

                    $message = ChatMessage::where([
                        ['id', $value],
                        ['sender_id', Auth::id()]
                    ])->whereDoesntHave('userDelete')->first();

                    if (is_null($message)) {
                        $fail("Invalid message!");
                    }
                }
            ]
        ]);
        try {
            $message->userDelete()->create([
                'user_id' => Auth::id()
            ]);

            return redirect()->route('chatIndex')->with('success', 'Message deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }
}
