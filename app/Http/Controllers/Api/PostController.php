<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentOnPost;
use App\Events\PostLiked;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function list (Request $request)
    {
        try {
            DB::beginTransaction();
            $auth_user = auth('api')->user();
            $posts = Post::select('id', 'content', 'location', 'feeling_text', 'feeling_icon', 'user_id', 'created_at', 'post_id')
                ->with([
                    'sharedPost' => function ($q) {
                        $q->select('id', 'content', 'user_id', 'created_at');
                    },
                ])
                ->whereHas('user', function ($q) use ($auth_user) {
                    return $q->whereNotBlockedBy($auth_user);
                })
                ->withCount('likers', 'comments')
                ->latest()
                ->simplePaginate(10)
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

                    $auth_user->attachLikeStatus($item);

                    $likers = $item->likers()->latest()->simplePaginate(3);
                    $r_likers = [];
                    foreach ($likers as $user) {
//                        $r_likers[] = $user->only('id', 'username', 'profile_image');
                        $r_likers[] = get_user_profile($user->id, false);
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
                            $item->sharedPost->user = get_user_profile($item->sharedPost->user->id, false);
                        }
                    }

                    $target_user = User::find($item->user->id);

                    //add author
                    $item->user = get_user_profile($item->user_id, false);

                    $item->is_blocked = $auth_user->isBlockedBy($target_user) || $auth_user->hasBlocked($target_user) || $target_user->isBlockedBy($auth_user) || $target_user->hasBlocked($auth_user);

                    return $item;
                });

            DB::commit();
            return response()->json(array_merge([ 'success' => true ], $posts->toArray()), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function create (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'content' => ['nullable', 'string'],
                'files' => ['nullable', 'max:5'],
                'post_id' => ['nullable', Rule::exists('posts', 'id')->whereNull('deleted_at')],
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $post = auth('api')->user()->posts()->create([
                'content' => $request['content'],
                'location' => $request['location'],
                'feeling_text' => $request['feeling_text'],
                'feeling_icon' => $request['feeling_icon']
            ]);
            if (isset($request['files'])) {
                $post->addMultipleMediaFromRequest(['files'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('post_upload');
                });
            }

            $post = get_post($post->id);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully.',
                'data' => $post->toArray(),
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function update (Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'content' => ['nullable', 'string'],
                'files' => ['nullable', 'max:5'],
                'post_id' => ['nullable', Rule::exists('posts', 'id')->whereNull('deleted_at')],
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            if (!$post = Post::find($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'errors' => []
                ], 401);
            }

            $post->content = $request['content'];
            $post->location = $request['location'];
            $post->feeling_text = $request['feeling_text'];
            $post->feeling_icon = $request['feeling_icon'];
            $post->save();

            if (isset($request['files'])) {
                $post->clearMediaCollection('post_upload');
                $post->addMultipleMediaFromRequest(['files'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('post_upload');
                });
            }

            $post = get_post($post->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully.',
                'data' => $post->toArray(),
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function delete (Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    Rule::exists('posts')
                        ->whereNull('deleted_at')
                        ->where('user_id', auth('api')->id())
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            if (!$post = Post::find($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'errors' => []
                ], 401);
            }

            $post->clearMediaCollection('post_upload');
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully.',
                'data' => [],
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function like (Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    Rule::exists('posts')
                        ->whereNull('deleted_at')
                        ->where('user_id', auth('api')->id())
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }


            $user = auth('api')->user();
            $post = Post::find($id);
            $auth = User::with('profile')->find($user->id);
            $target = $post->user;

            $user->toggleLike($post);
            $isLike = $user->hasLiked($post) ? 'liked' : 'unliked';

            //notification when post liked
            if($isLike == 'liked') {
                if($auth->id != $target->id) {
                    $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " has liked your post.";
                    $notification = Notification::create([
                        'user_id' => $target->id,
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $target->id,
                        'body' => $string,
                        'sender_id' => $target->id,
                        'post_id' => $post->id,
                        'sender_pic' => $user->get_profile_picture(),
                    ]);
                    event(new PostLiked($target->id, $string, 'App\Models\User', $notification->id, $target));
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Post $isLike successfully!",
                'data' => [],
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }

    public function comment (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'post_id' => ['required', 'string', Rule::exists('posts', 'id')->whereNull('deleted_at')],
                'comment' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $user = auth('api')->user();
            $post = Post::find($request->post_id);
            $auth = User::with('profile')->find($user->id);
            $target = $post->user;

            $post->comments()->create([
                'user_id' => $user->id,
                'comment' => $request->comment
            ]);

            //notification when comment on post
            if($auth->id != $target->id) {
                $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " commented on your post.";
                $notification = Notification::create([
                    'user_id' => $target->id,
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $target->id,
                    'body' => $string,
                    'sender_id' => $target->id,
                    'post_id' => $post->id,
                    'sender_pic' => $user->get_profile_picture(),
                ]);
                event(new CommentOnPost($target->id, $string, 'App\Models\User', $notification->id, $target));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully!',
                'data' => [],
                'errors' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'errors' => [],
            ], 401);
        }
    }
}
