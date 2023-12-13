<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentLiked;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function like (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'comment_id' => ['required', 'string', Rule::exists('comments', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $user = auth('api')->user();
            $comment = Comment::find($request->comment_id);
            $auth = User::with('profile')->find($user->id);
            $target = $comment->user;
            $post = Post::findOrFail($comment->commentable_id);

            $user->toggleLike($comment);

            $isLike = $user->hasLiked($comment) ? 'liked' : 'unliked';

            //notification when like on comment
            if($isLike == 'liked') {
                if($auth->id != $target->id) {
                    $string = ($auth->profile->first_name . ' ' . $auth->profile->last_name) . " liked your comment.";
                    $notification = Notification::create([
                        'user_id' => $target->id,
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $target->id,
                        'body' => $string,
                        'sender_id' => $target->id,
                        'post_id' => $post->id,
                        'sender_pic' => $user->get_profile_picture(),
                    ]);
                    event(new CommentLiked($target->id, $string, 'App\Models\User', $notification->id, $target));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Comment $isLike successfully!",
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

    public function delete (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'comment_id' => ['required', 'string', Rule::exists('comments')
                    ->where('user_id', auth('api')->id())
                    ->whereNull('deleted_at')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $user = auth('api')->user();

            $user->comments()->where([
                ['id', $request->comment_id],
            ])->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Comment deleted successfully!",
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

    public function reply (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'comment_id' => ['required', 'string', Rule::exists('comments', 'id')->whereNull('deleted_at')],
                'reply' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            $user = auth('api')->user();

            $user->comments()->where([
                ['id', $request->comment_id],
            ])->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Comment deleted successfully!",
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
