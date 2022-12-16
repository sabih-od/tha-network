<?php

namespace App\Traits;


use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait PostData
{
    protected function getPostData($auth = false, $another_user = null, $post_id = null)
    {
        $auth_user = Auth::user();
        $query = Post::select('id', 'content', 'location', 'feeling_text', 'feeling_icon', 'user_id', 'created_at', 'post_id')
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'username');
                },
                'sharedPost' => function ($q) {
                    $q->select('id', 'content', 'user_id', 'created_at');
                }
            ])
            ->withCount('likers', 'comments')
            ->when($post_id, function($q) use($post_id) {
                return $q->where('id', '!=', $post_id);
            })
            ->latest();

        if ($auth || !is_null($another_user))
            $query = $query->where('user_id', $auth ? $auth_user->id : $another_user->id);

        $query = $query
            ->simplePaginate(5)
            ->through(function ($item, $key) use ($auth_user) {
                // add media in item
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
                /*if ($item->user) {
//                    $item->user->profile_img = $item->user->getFirstMediaUrl('profile_image');

                    // follow user
                    if ($item->user->id != $auth_user->id) {
                        $item->user->is_followed = $auth_user->isFollowing($item->user);
                    }
                }*/

                $auth_user->attachLikeStatus($item);

                $likers = $item->likers()->latest()->simplePaginate(3);
                $r_likers = [];
                foreach ($likers as $user) {
                    $r_likers[] = $user->only('id', 'username', 'profile_image');
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
//                        $item->sharedPost->user->profile_img = $item->sharedPost->user->getFirstMediaUrl('profile_image');
                        // follow user
                        /*if ($item->sharedPost->user->id != $auth_user->id) {
                            $item->sharedPost->user->is_followed = $auth_user->isFollowing($item->sharedPost->user);
                        }*/
                    }
                }

                //block data in item
                //                'is_blocked_by_user' => $auth->isBlockedBy($user),
//                'has_blocked' => $auth->hasBlocked($user),
                $target_user = User::find($item->user->id);
                $item->is_blocked_by_user = $auth_user->isBlockedBy($target_user);
                $item->has_blocked = $auth_user->hasBlocked($target_user);
                $item->new_block_property = $auth_user->isBlockedBy($target_user) || $auth_user->hasBlocked($target_user) || $target_user->isBlockedBy($auth_user) || $target_user->hasBlocked($auth_user);

                return $item;
            });

//        dd($query);
        if(!is_null($post_id)) {
            $post = Post::select('id', 'content', 'location', 'feeling_text', 'feeling_icon', 'user_id', 'created_at', 'post_id')
                ->with([
                    'user' => function ($q) {
                        $q->select('id', 'username');
                    },
                    'sharedPost' => function ($q) {
                        $q->select('id', 'content', 'user_id', 'created_at');
                    }
                ])
                ->withCount('likers', 'comments')
                ->find($post_id);

            $post->getMedia('post_upload');
            $files = [];
            foreach ($post->media as $media) {
                $files[] = [
                    'mime_type' => $media->mime_type,
                    'url' => $media->original_url,
                ];
            }
            $post->media_items = $files;

            $auth_user->attachLikeStatus($post);

            $likers = $post->likers()->latest()->simplePaginate(3);
            $r_likers = [];
            foreach ($likers as $user) {
                $r_likers[] = $user->only('id', 'username', 'profile_image');
            }
            $post->recent_likes = $r_likers;

            // share post data
            if ($post->sharedPost) {
                $post->sharedPost->getMedia('post_upload');
                $s_files = [];
                foreach ($post->sharedPost->media as $media) {
                    $s_files[] = [
                        'mime_type' => $media->mime_type,
                        'url' => $media->original_url,
                    ];
                }
                $post->sharedPost->media_items = $s_files;
                // add profile image in item
                if ($post->sharedPost->user) {
                    unset(
                        $post->sharedPost->user->created_at,
                        $post->sharedPost->user->deleted_at,
                        $post->sharedPost->user->email_verified_at,
                        $post->sharedPost->user->updated_at
                    );
                }
            }

            $query->prepend($post);
        }


        return $query;
    }
}
