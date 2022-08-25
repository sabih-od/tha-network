<?php

namespace App\Traits;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

trait ChatData
{
    protected function getChannelsData(Request $request)
    {
        $user_delete_record = Channel::select('id', 'creator_id', 'participants', 'chat_type', 'created_at')
            ->whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            })
            ->whereHas('userDelete', function ($q) {
                $q->where('user_id', Auth::id());
            })->first();
//        dd(is_null($user_delete_record));
//        dd($user_delete_record);
        $query = Channel::select('id', 'creator_id', 'participants', 'chat_type')
//            ->with('creator.profile')
//            ->with('creator')
            ->whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            })->whereDoesntHave('userDelete', function ($q) {
                $q->where('user_id', Auth::id());
            });
//            ->when(is_null($user_delete_record), function($q) {
//                $q->whereDoesntHave('userDelete', function ($q) {
//                    $q->where('user_id', Auth::id());
//                });
//            })
//            ->when($user_delete_record, function($q) use($user_delete_record) {
////                $q->whereHas('deleteable', function ($q) use ($user_delete_record) {
////                    $q->where('user_id', Auth::id())
////                        ->whereHas('channels', function($q) use($user_delete_record) {
////                            $q->whereHas('messages', function($q) use($user_delete_record) {
////                                $q->where('created_at', '>', $user_delete_record->created_at);
////                            });
////                        });
////                });
//
////                $q->whereHas('userDelete', function($q) use($user_delete_record){
////                    $q->where('user_id', Auth::id())
////                        ->whereHas('channels2', function($q) use($user_delete_record) {
////                            $q->whereHas('messages', function($q) use($user_delete_record) {
////                                $q->where('created_at', '>', $user_delete_record->userDelete->created_at);
////                            });
////                        });
////                });
//
////                $q->whereHas('messages', function($q) use($user_delete_record) {
////                        $q->where('created_at', '>', $user_delete_record->created_at);
////                    })->whereHas('userDelete');
//            });

        if (!is_null($request->get('search'))) {
            $query->whereHas('users', function($q) use ($request) {
                return $q->where('id', '!=', Auth::id())
                        ->whereHas('profile', function($q) use ($request) {
                            return $q->where('first_name', 'like', "%{$request->search}%")
                                    ->orWhere('last_name', 'like', "%{$request->search}%");
                        });
            });
        }

        return $query
            ->simplePaginate(10)
            ->through(function ($item, $key) {
                $cover_data = null;
                if ($item->chat_type == 'individual') {
                    $cover_data = $item->users()
                        ->select('id', 'username')
                        ->with('profile')
                        ->where('id', '<>', Auth::id())
                        ->first();

                    $cover_data->profile_img = $cover_data->getFirstMedia('profile_image')->original_url ?? null;
                }elseif ($item->chat_type == 'group') {
                    $cover_data = $item->group()
                        ->select('id', 'username')
                        ->first();

                    $cover_data->profile_img = $cover_data->getFirstMedia('group_media')->original_url ?? null;
                }

                $item->cover_detail = $cover_data;

                unset(
                    $item->participants,
                    $item->chat_type,
                );


//                $item->auth_id = Auth::id();
//                $item->profile_img = $item->getFirstMedia('profile_image')->original_url ?? null;

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

    protected function getMessagesData(Request $request)
    {
        $channel = Channel::select('id', 'participants', 'chat_type')
            ->whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            })->where('id', $request->channel_id)
            ->first();

        if (is_null($channel))
            return [];

        /*$query = Channel::select('id', 'participants', 'chat_type')
            ->whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            });*/

        /*if (!is_null($request->get('search'))) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }*/

        return $channel
            ->messages()
            ->select('id', 'content', 'sender_id', 'created_at', 'channel_id')
            ->with(['sender' => function ($q) {
                $q->select('id', 'username');
            }])
            ->whereDoesntHave('userDelete', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->simplePaginate(15)
            ->through(function ($item, $key) {
                if (!is_null($item->sender))
                    $item->sender->profile_img = $item->sender->getFirstMedia('profile_image')->original_url ?? null;

                $media = $item->getFirstMedia('media');
                $item->file = $media ? [
                    'mime_type' => $media->mime_type,
                    'url' => $media->original_url,
                ] : null;

                unset(
                    $item->sender_id,
                    $item->channel_id,
                );

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
}
