<?php

use App\Models\Goal;
use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
function last_active($user_id) {
    $user = \App\Models\User::find($user_id);
    $last_activity = Carbon::parse($user->last_activity);

    if(is_null($last_activity)) {
        return 'Online';
    }

    $now = Carbon::now();

    $difference = $last_activity->diff($now);
    $string = "Active ";

    $string .= ($difference->y == 0 ? '' : $difference->y . 'y ');
    $string .= ($difference->m == 0 ? '' : $difference->m . 'm ');
    $string .= ($difference->d == 0 ? '' : $difference->d . 'd ');
    $string .= ($difference->h == 0 ? '' : $difference->h . 'h ');
    $string .= ($difference->i == 0 ? '' : $difference->i . 'm ');
    $string .= 'ago';

    if($string == 'Active ago'){
        $string = 'Online';
    }

    return $string;
}

function get_eloquent_user($id = null) {
    return User::find($id ?? Auth::id());
}

function get_my_rank($id = null) {
    $user = get_eloquent_user($id);

    return Goal::where('target', '>', $user->completed_referrals->count())->orderBy('target', 'ASC')->first();
}

function get_referrals_by_day($date) {
    return Referral::where('status', true)->where('user_id', Auth::id())->whereDate('updated_at', $date)->get()->count();
}

function get_weekly_goals($id = null) {
    $rank = get_my_rank($id);
    $user = get_eloquent_user($id);
    $today = Carbon::now();
    $end_of_this_month = (Carbon::now())->endOfMonth();
    $weeks_remaining = $today->diffInWeeks($end_of_this_month) + 1;

    $weekly_goals = $user->remaining_referrals / $weeks_remaining;
    $referrals_made = $user->completed_referrals_this_week()->count();
    $remaining_goals = $weekly_goals - $referrals_made;

    $monday = Carbon::now()->startOfWeek();
    $tuesday = $monday->copy()->addDay();
    $wednesday = $tuesday->copy()->addDay();
    $thursday = $wednesday->copy()->addDay();
    $friday = $thursday->copy()->addDay();
    $saturday = $friday->copy()->addDay();
    $sunday = $saturday->copy()->addDay();
    $weekly_list = [
        ['day' => ucfirst('monday'), 'count' => get_referrals_by_day($monday)],
        ['day' => ucfirst('tuesday'), 'count' => get_referrals_by_day($tuesday)],
        ['day' => ucfirst('wednesday'), 'count' => get_referrals_by_day($wednesday)],
        ['day' => ucfirst('thursday'), 'count' => get_referrals_by_day($thursday)],
        ['day' => ucfirst('friday'), 'count' => get_referrals_by_day($friday)],
        ['day' => ucfirst('saturday'), 'count' => get_referrals_by_day($saturday)],
        ['day' => ucfirst('sunday'), 'count' => get_referrals_by_day($sunday)],
    ];

    return [
        'weekly_goals' => $weekly_goals,
        'referrals_made' => $referrals_made,
        'weekly_list' => $weekly_list,
        'remaining_goals' => $remaining_goals,
    ];
}

function last_weeks_rankings() {
    $start_of_last_week = (Carbon::now())->subWeek()->startofWeek()->format('Y-m-d H:i');
    $end_of_last_week = (Carbon::now())->subWeek()->endofWeek()->format('Y-m-d H:i');

    $referrals = Referral::where('status', true)
        ->with('user.profile')
        ->whereDate('updated_at', '>=', $start_of_last_week)
        ->whereDate('updated_at', '<=', $end_of_last_week)
        ->groupBy('user_id')
        ->select(DB::raw('*'), DB::raw('count(*) as total'))
        ->orderBy('total', 'DESC')
        ->take(3)
        ->get();

    $string = "WOW, Last week was a Great Week for the following members" . "\r\n";

    foreach ($referrals as $key => $referral) {
        $name = $referral->user->profile->first_name . ' ' . $referral->user->profile->last_name;
        $string .= (string)addOrdinalNumberSuffix($key + 1) . " Place (" . $name . ", " . $referral->total . " referrals)!!!" . "\r\n";
    }
    $string .= "Congratulations And Keep The Momentum Going!!!";

    return $string;
}

function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
        switch ($num % 10) {
            // Handle 1st, 2nd, 3rd
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
        }
    }
    return $num.'th';
}