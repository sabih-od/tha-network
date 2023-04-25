<?php

namespace App\Traits;


use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

trait CommentData
{
    protected function getCommentData($post_id)
    {
        $auth_user = Auth::user();
        $post = Post::find($post_id);

        if (is_null($post))
            return [];

        return $post->comments()
            ->select('id', 'comment', 'commentable_id', 'commentable_type', 'user_id', 'created_at')
            ->latest()
            ->with(['user' => function ($q) {
                $q->select('id', 'username');
            }, 'user.profile'])
            ->withCount('replies')
            ->simplePaginate(5)
            ->through(function ($item, $key) use($auth_user) {
                $auth_user->attachLikeStatus($item);
                unset(
                    $item->user_id,
                    $item->commentable_id,
                    $item->commentable_type
                );

                return $item;
            });
    }

    protected function getReplyData($comment_id)
    {
        $auth_user = Auth::user();
        $comment = Comment::find($comment_id);

        if (is_null($comment))
            return [];

        return $comment->replies()
            ->select('id', 'comment', 'commentable_id', 'commentable_type', 'user_id', 'created_at')
            ->latest()
            ->with(['user' => function ($q) {
                $q->select('id', 'username');
            }, 'user.profile'])
            ->simplePaginate(5)
            ->through(function ($item, $key) use($auth_user) {
                $auth_user->attachLikeStatus($item);
                unset(
                    $item->user_id,
                    $item->commentable_id,
                    $item->commentable_type
                );

                return $item;
            });
    }
}
