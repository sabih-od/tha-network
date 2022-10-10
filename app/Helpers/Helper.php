<?php

use App\Events\NoNotificationForTheDay;
use App\Events\NoReferralsForTheDay;
use App\Events\UnableToMeetWeeklyGoal;
use App\Events\WeeklyRankingNotification;
use App\Models\Goal;
use App\Models\Notification;
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

function get_referrals_by_day($date, $id = null) {
    return Referral::where('status', true)->where('user_id', $id ?? Auth::id())->whereDate('updated_at', $date)->get()->count();
}

function get_weekly_goals($id = null) {
    $rank = get_my_rank($id);
    $user = get_eloquent_user($id);
    $today = Carbon::now();
    $end_of_this_month = (Carbon::now())->endOfMonth();
    $weeks_remaining = $today->diffInWeeks($end_of_this_month) + 1;

    $weekly_goals = intval($user->remaining_referrals / $weeks_remaining);
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
        ['day' => ucfirst('monday'), 'count' => get_referrals_by_day($monday, $id)],
        ['day' => ucfirst('tuesday'), 'count' => get_referrals_by_day($tuesday, $id)],
        ['day' => ucfirst('wednesday'), 'count' => get_referrals_by_day($wednesday, $id)],
        ['day' => ucfirst('thursday'), 'count' => get_referrals_by_day($thursday, $id)],
        ['day' => ucfirst('friday'), 'count' => get_referrals_by_day($friday, $id)],
        ['day' => ucfirst('saturday'), 'count' => get_referrals_by_day($saturday, $id)],
        ['day' => ucfirst('sunday'), 'count' => get_referrals_by_day($sunday, $id)],
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

    $users = User::with('profile')->where('role_id', 2)->get();
    foreach ($users as $user) {
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);

        event(new WeeklyRankingNotification($user->id, $string, 'App\Models\User', $notification->id, $user));
    }

    return $string;
}

function unable_to_meet_weekly_goal() {
    $users = User::with('profile')->where('role_id', 2)->get();
    foreach ($users as $user) {
        $today = Carbon::now();
        $end_of_this_month = (Carbon::now())->endOfMonth();
        $weeks_remaining = $today->diffInWeeks($end_of_this_month) + 1;

        $weekly_goals = intval($user->remaining_referrals / $weeks_remaining);
        $referrals_made = $user->completed_referrals_this_week()->count();

        if($referrals_made < $weekly_goals) {
            $string = "You did not meet your weekly goal this week, but better luck next week!! ";

            $notification = Notification::create([
                'user_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'body' => $string,
                'sender_id' => $user->id
            ]);

            event(new UnableToMeetWeeklyGoal($user->id, $string, 'App\Models\User', $notification->id, $user));
        }
    }
}

function no_notification_for_the_day() {
    $users = User::with('profile')->where('role_id', 2)->get();
    foreach ($users as $user) {
        $today = Carbon::now();
        $end_of_this_month = (Carbon::now())->endOfMonth();
        $weeks_remaining = $today->diffInWeeks($end_of_this_month) + 1;
        $weekly_goals = intval($user->remaining_referrals / $weeks_remaining);

        if($user->completed_referrals_today()->count() == 0 && $user->completed_referrals_this_week()->count() < $weekly_goals) {
            $string = "Hi, you haven’t sent any referrals today, you’re (".$weekly_goals.") referrals away from completing your weekly goal!!";

            $notification = Notification::create([
                'user_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'body' => $string,
                'sender_id' => $user->id
            ]);

            event(new NoReferralsForTheDay($user->id, $string, 'App\Models\User', $notification->id, $user));
        }
    }
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
