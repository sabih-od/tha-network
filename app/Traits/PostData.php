<?php

namespace App\Traits;


use App\Models\Post;
use Illuminate\Support\Facades\Auth;

trait PostData
{
    protected function getPostData($auth = false, $another_user = null)
    {
        $auth_user = Auth::user();
        $query = Post::select('id', 'content', 'location', 'user_id', 'created_at', 'post_id')
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'username');
                },
                'sharedPost' => function ($q) {
                    $q->select('id', 'content', 'user_id', 'created_at');
                }
            ])
            ->withCount('likers', 'comments')
            ->latest();

        if ($auth || !is_null($another_user))
            $query = $query->where('user_id', $auth ? $auth_user->id : $another_user->id);

        return $query
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
                return $item;
            });
    }
}
