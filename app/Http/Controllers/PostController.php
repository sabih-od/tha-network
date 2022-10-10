<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function store(Request $request)
    {
//        dd($request->all());
        $data = $request->validate([
            'content' => ['required', 'string'],
            'files' => ['nullable', 'max:5'],
            'files.*.file' => [function ($attribute, $value, $fail) {
                if (!$value) return;
                $is_image = Validator::make(
                    ['upload' => $value],
                    ['upload' => 'image']
                )->passes();

                $is_video = Validator::make(
                    ['upload' => $value],
                    ['upload' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4']
                )->passes();

                if (!$is_video && !$is_image) {
                    $fail(':attribute must be image or video.');
                }

                if ($is_video) {
                    $validator = Validator::make(
                        ['video' => $value],
                        ['video' => "max:102400"]
                    );
                    if ($validator->fails()) {
                        $fail(":attribute must be 10 megabytes or less.");
                    }
                }

                if ($is_image) {
                    $validator = Validator::make(
                        ['image' => $value],
                        ['image' => "max:102400"]
                    );
                    if ($validator->fails()) {
                        $fail(":attribute must be 10 megabytes or less.");
                    }
                }
            }],
            'location' => 'sometimes',
            'feeling_text' => 'sometimes',
            'feeling_icon' => 'sometimes'
        ]);

        try {
            $post = Auth::user()->posts()->create([
                'content' => $data['content'],
                'location' => $data['location'],
                'feeling_text' => $data['feeling_text'],
                'feeling_icon' => $data['feeling_icon']
            ]);
            if (isset($data['files'])) {
                $post->addMultipleMediaFromRequest(['files'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('post_upload');
                });
            }
            return redirect(url()->previous(true))->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function sharePost(Request $request)
    {
        if (!Auth::check())
            return redirect(url()->previous(true))->with('error', 'Login required!');

        $data = $request->validate([
            'post_id' => ['required', 'string', Rule::exists('posts', 'id')->whereNull('deleted_at')],
            'content' => ['required', 'string'],
        ]);

        try {
            Auth::user()->posts()->create([
                'post_id' => $data['post_id'],
                'content' => $data['content']
            ]);

            return redirect(url()->previous(true))->with('success', 'Post shared successfully!');
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function postLikeToggle(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'string', Rule::exists('posts', 'id')->whereNull('deleted_at')],
        ]);
        try {
            $user = Auth::user();
            $post = Post::find($request->post_id);

            $user->toggleLike($post);

            $isLike = $user->hasLiked($post) ? 'liked' : 'unliked';
            return redirect(url()->previous(true))->with('success', "Post $isLike successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function postCommentStore(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'string', Rule::exists('posts', 'id')->whereNull('deleted_at')],
            'comment' => 'required|string'
        ]);
        try {
            $user = Auth::user();
            $post = Post::find($request->post_id);

            $post->comments()->create([
                'user_id' => $user->id,
                'comment' => $request->comment
            ]);

            return redirect(url()->previous(true))->with('success', "Comment posted successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function postCommentDelete(Request $request)
    {
        if (!Auth::check())
            return redirect(url()->previous(true))->with('error', 'Login required!');

        $request->validate([
            'id' => ['required', 'string', Rule::exists('comments')
                ->where('user_id', Auth::id())
                ->whereNull('deleted_at')
            ],
        ]);
        try {
            $user = Auth::user();

            $user->comments()->where([
                ['id', $request->id],
            ])->delete();

            return redirect(url()->previous(true))->with('success', "Comment deleted successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function commentReplyStore(Request $request)
    {
        if (!Auth::check())
            return redirect(url()->previous(true))->with('error', 'Login required!');

        $request->validate([
            'comment_id' => ['required', 'string', Rule::exists('comments', 'id')->whereNull('deleted_at')],
            'reply' => 'required|string'
        ]);
        try {
            $user = Auth::user();
            $comment = Comment::find($request->comment_id);

            $comment->replies()->create([
                'user_id' => $user->id,
                'comment' => $request->reply
            ]);

            return redirect(url()->previous(true))->with('success', "Reply posted successfully!");
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $data_validation = array_merge_recursive(
            $request->all(),
            [
                'id' => $id
            ]
        );

        $validation = Validator::make($data_validation, [
            'id' => [
                'required',
                Rule::exists('posts')
                    ->whereNull('deleted_at')
                    ->where('user_id', Auth::id())
            ]
        ]);

        if ($validation->fails())
            return redirect()->back()->withErrors($validation->getMessageBag());

        try {
            Auth::user()->posts()->where('id', $id)->delete();

            return redirect(url()->previous(true))->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            return redirect(url()->previous(true))->with('error', $e->getMessage());
        }
    }
}
