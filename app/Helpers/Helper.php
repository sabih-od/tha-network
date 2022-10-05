<?php

use App\Models\Referral;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
