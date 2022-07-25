<?php

namespace App\Http\Controllers;

//use App\Traits\CommentData;
//use App\Traits\PostData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
//    use PostData, CommentData;

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
        if(!is_null($profile))
            $profile = $profile->only('first_name', 'last_name');
        return Inertia::render('Home', [
            'profile' => $profile
//            'posts' => Inertia::lazy(function () {
//                return $this->getPostData();
//            }),
//            'comments' => Inertia::lazy(function () use ($request) {
//                return $this->getCommentData($request->post_id ?? null);
//            }),
//            'replies' => Inertia::lazy(function () use ($request) {
//                return $this->getReplyData($request->comment_id ?? null);
//            }),
        ]);
    }
}
