<?php

namespace App\Http\Controllers;

use App\Traits\CommentData;
use App\Traits\PostData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    use PostData, CommentData;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     */
    public function index(Request $request)
    {
        $profile = Auth::user()->profile ?? null;
//        if(!is_null($profile))
//            $profile = $profile->only('first_name', 'last_name');
        return Inertia::render('Home', [
            'user' => Auth::user()->only('id', 'username', 'email', 'created_at') ?? null,
            'profile' => $profile,
            'goals' => get_weekly_goals(),
            'posts' => Inertia::lazy(function () use ($request) {
                $is_my_post = boolval($request->get('is_my_posts', 0));
                return $this->getPostData($is_my_post);
            }),
            'comments' => Inertia::lazy(function () use ($request) {
                return $this->getCommentData($request->post_id ?? null);
            }),
            'replies' => Inertia::lazy(function () use ($request) {
                return $this->getReplyData($request->comment_id ?? null);
            }),
            'profile_cover' => $this->profileImg(Auth::user(), 'profile_cover'),
            'friends_count' => count(Auth::user()->followers) - 1,
            'network_count' => Auth::user()->network()->exists() ? count(Auth::user()->network->members) : 0,
        ]);
    }

    private function profileImg($user, $collection)
    {
        $img = null;
        if ($user) {
            $img = $user->getFirstMedia($collection)->original_url ?? null;
        }
        return $img;
    }
}
