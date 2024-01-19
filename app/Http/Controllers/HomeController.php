<?php

namespace App\Http\Controllers;

use App\Models\RewardLog;
use App\Traits\CommentData;
use App\Traits\PostData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\StripeClient;

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

        //check if user has linked any accounts to their stripe payout screen
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $user = Auth::user();
        $has_provided_stripe_payout_information = false;
        if ($user && $user->stripe_account_id) {
            $account = $stripe->accounts->retrieve($user->stripe_account_id);
            $has_provided_stripe_payout_information = (bool)($account->external_accounts->total_count > 0);
        }

        //reward logs
        $reward_logs = RewardLog::whereHas('reward', function ($q) {
            return $q->whereHas('user')->whereHas('invited_user')->where('user_id', Auth::id());
        })->orderBy('created_at', 'DESC')->get();

        $earnings = 0;
        foreach ($reward_logs as $reward_log) {
            $earnings += $reward_log->reward->amount;
        }

        return Inertia::render('Home', [
            'user' => Auth::user()->only('id', 'username', 'email', 'created_at') ?? null,
            'profile' => $profile,
            'goals' => get_weekly_goals(),
            'posts' => Inertia::lazy(function () use ($request) {
                $is_my_post = boolval($request->get('is_my_posts', 0));
                $post_id = $request->get('post_id');
                return $this->getPostData($is_my_post, null, $post_id, );
            }),
            'comments' => Inertia::lazy(function () use ($request) {
                return $this->getCommentData($request->post_id ?? null);
            }),
            'replies' => Inertia::lazy(function () use ($request) {
                return $this->getReplyData($request->comment_id ?? null);
            }),
            'profile_cover' => $this->profileImg(Auth::user(), 'profile_cover'),
            'friends_count' => count(Auth::user()->followers),
            'network_count' => Auth::user()->network()->exists() ? count(Auth::user()->network->members) : 0,
            'level_details' => get_my_level(Auth::user()->id),
            'paypal_account_details' => auth()->check() ? auth()->user()->paypal_account_details : '',
            'has_provided_stripe_payout_information' => $has_provided_stripe_payout_information,
            'earnings' => '$' . strval($earnings),
            'monthly_earnings' => get_my_monthly_earnings(),
            'year_to_date_earnings' => get_my_year_to_date_earnings(),
            'gross_earnings' => get_my_gross_earnings(),
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
