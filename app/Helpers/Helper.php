<?php

use App\Events\NetworkMemberClosure;
use App\Events\NoReferralsForTheDay;
use App\Events\PaymentNotMade;
use App\Events\ReferralReverted;
use App\Events\SetWeeklyGoal;
use App\Events\UnableToMeetWeeklyGoal;
use App\Events\WeeklyRankingNotification;
use App\Models\Channel;
use App\Models\Goal;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\RewardLog;
use App\Models\Settings;
use App\Models\ThaPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\ProductionEnvironment;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use Stripe\Stripe;

function last_active($user_id): string
{
    $user = User::find($user_id);

    if(is_null($user->last_activity)) {
        return 'Online';
    }

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

function get_eloquent_users($id = null) {
    return User::with('profile')->where('role_id', 2)->get();
}

function get_my_rank($id = null) {
    $user = get_eloquent_user($id);

    $goal = Goal::where('target', '>', $user->completed_referrals->count())->orderBy('target', 'ASC')->first();

    //remove if buggy
    if (!$goal) {
        $goal = Goal::query()->orderBy('target','DESC')->first();
    }////

    return $goal;
}

function get_my_level($id = null): array
{
    $goal = get_my_rank($id);

    switch ($goal->name) {
        case 'Beginner':
            $level = 'Bronze';
            $trophy = asset('images/trophies/' . $level . '.png');
            break;

        case 'Amateur':
            $level = 'Silver';
            $trophy = asset('images/trophies/' . $level . '.png');
            break;

        case 'Expert':
            $level = 'Gold';
            $trophy = asset('images/trophies/' . $level . '.png');
            break;

        case 'THA King':
            $level = 'Platinum';
            $trophy = asset('images/trophies/' . $level . '.png');
            break;

        default:
            $level = '';
            $trophy = '';
    }

    return [
        'level' => $level,
        'trophy' => $trophy
    ];
}

function get_referrals_by_day($date, $id = null) {
    return Referral::where('status', true)->where('user_id', $id ?? Auth::id())->whereDate('updated_at', $date)->get()->count();
}

function monthly_add_goals() {
    $users = get_eloquent_users();
    foreach ($users as $user) {
        $rank = get_my_rank($user->id);
        $user->remaining_referrals = intval($user->remaining_referrals) + intval($rank->target);
        $user->save();
    }
}

function get_weekly_goals($id = null): array
{
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

function last_weeks_rankings(): string
{
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

    if(count($referrals) == 0) {
        return '';
    }

    $string = "WOW, Last week was a Great Week for the following members" . "\r\n";

    foreach ($referrals as $key => $referral) {
        $name = $referral->user->profile->first_name . ' ' . $referral->user->profile->last_name;
        $string .= (string)addOrdinalNumberSuffix($key + 1) . " Place (" . $name . ", " . $referral->total . " referrals)!!!" . "\r\n";
    }
    $string .= "Congratulations And Keep The Momentum Going!!!";

    $users = get_eloquent_users();
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
    $users = get_eloquent_users();
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

function no_referrals_for_the_day() {
    $users = get_eloquent_users();
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

function set_weekly_goal() {
    $users = get_eloquent_users();
    foreach ($users as $user) {
        $string = "Your Weekly goals have been set. Complete your goals to get promoted to the next grade";
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);

        event(new SetWeeklyGoal($user->id, $string, 'App\Models\User', $notification->id, $user));
    }
}

function addOrdinalNumberSuffix($num): string
{
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

function has_made_monthly_payment($id = null): bool
{
    $user = get_eloquent_user($id);

    if($user->stripe_checkout_session_id == null) {
        return false;
    }

    $stripe = new \Stripe\StripeClient(
        env('STRIPE_SECRET_KEY')
    );

    try {
        $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);
    } catch(\Exception $e) {
        return false;
    }

//    dump(Carbon::parse($subscription->trial_end)->format('m d Y'));
//    dd($stripe->invoices->retrieve($subscription->latest_invoice));

    $statuses_for_payment = ["trialing", "active"];
    if (in_array($subscription->status, $statuses_for_payment)) {
        return true;
    }

    $statuses_for_non_payment = ["incomplete", "incomplete_expired", "past_due", "canceled", "unpaid"];
    if (in_array($subscription->status, $statuses_for_non_payment)) {
        return false;
    }

//    $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
//
//    return ($latest_invoice->status == "paid");
}

function payment_not_made() {
    Log::info('payment_not_made: start');
    $users = get_eloquent_users();
    foreach ($users as $user) {
        try {
//            if(!has_made_monthly_payment($user->id)) {
            if(!has_made_monthly_payment($user->id) || $user->payment_retries > 0) {
                $string = "Hi, we have not received your monthly membership payment.\r\n
            Update your payment information before the 7th of the month.\r\n
            If you do not update your payment by the 7th at 11:59 pm central time your membership will be suspended until a payment is made and you will not receive referral payments for this month.\r\n
            Once payment is received your membership status will be updated and payments will continue in the next billing cycle.\r\n
            If your payment is not received by 11:59 pm on the 11th of this month, you will no longer receive referral payments, your account will be closed and you will lose all of your Network Members!!!\r\n
            Please update your account before the 11th of the month!!!!!";
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'body' => $string,
                    'sender_id' => $user->id
                ]);

                Log::info('payment_not_made | user: ' . $user->id);
                event(new PaymentNotMade($user->id, $string, 'App\Models\User', $notification->id, $user));

                //send mail to user
                $string = str_replace("\r\n", "<br />", $string);
                try {
                    referral_reversion_mail($user->email, 'Tha Network Delinquency Notice', $string);
                } catch (\Exception $e) {
                    Log::error('payment_not_made: mail ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('payment_not_made: catch ' . $e->getMessage());
        }
    }
    Log::info('payment_not_made: end');
}

function suspend_accounts() {
    $users = get_eloquent_users();
    foreach ($users as $user) {
        if(!has_made_monthly_payment($user->id)) {
            if(is_null($user->suspended_on)) {
                $user->suspended_on = Carbon::today();
                $user->save();
            }
        }
    }
}

function close_accounts() {
    Log::info('close_account: Function Start');
    $users = get_eloquent_users();
    foreach ($users as $user) {
        try {
//            DB::beginTransaction();

            if (!is_null($user->closed_on)) {
                continue;
            }

            if(!has_made_monthly_payment($user->id)) {
                //close account
//            if(is_null($user->suspended_on)) {
                $user->closed_on = Carbon::today();
                $user->save();
//            }

                //get what networks the user is member of
                $joined_networks_ids = NetworkMember::where('user_id', $user->id)->pluck('network_id');
                //get owners of those networks
                $joined_networks_owner_ids = Network::whereHas('user')->whereIn('id', $joined_networks_ids)->pluck('user_id');
                //send notification to owners
                foreach ($joined_networks_owner_ids as $target_id) {
                    $string = $user->profile->first_name . ' ' . $user->profile->last_name . " is no longer a member of the network so you will not earn your referral fee for this member any longer.";
                    $target = User::with('profile')->find($target_id);
                    $notification = Notification::create([
                        'user_id' => $target->id,
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $target->id,
                        'body' => $string,
                        'sender_id' => $target->id
                    ]);

                    event(new NetworkMemberClosure($target->id, $string, 'App\Models\User', $notification->id, $target));
                }

                //pause subscription
                toggle_user_subscription($user->id);

                //send referral reversion notification to inviter
                $inviter_id = get_inviter_id($user->id);
                $string = "Your ".$user->profile->first_name . ' ' . $user->profile->last_name." referral is no longer a member of the network you you won’t be receiving its referral payment";
                $target = User::with('profile')->find($inviter_id);
                if ($target) {
                    $notification = Notification::create([
                        'user_id' => $target->id,
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $target->id,
                        'body' => $string,
                        'sender_id' => $target->id,
                        'sender_pic' => $user->get_profile_picture(),
                    ]);
                    event(new ReferralReverted($target->id, $string, 'App\Models\User', $notification->id, $target));
                }

                //remove user from all networks
                NetworkMember::where('user_id', $user->id)->delete();

                //remove user from all friends lists
                DB::table('user_follower')->where('following_id', $user->id)->orWhere('follower_id', $user->id)->delete();

                try {
                    //send account closure email to user
//            $string = "Dear User, your Tha Network account has been closed. Contact Administration for further details.";
                    $string = "Due to the lack of payment for this month, your account has been closed, you will not receive any additional payments, and you have lost all of your network members.\r\n\r\n
                        If you decide to rejoin Tha Network, you will need to be invited or request a new referral code from the login in page.\r\n\r\n
                        Thanks for giving Tha Network a try!!!";
                    $string = str_replace("\r\n", "<br />", $string);
                    account_closure_mail($user->email, 'Tha Network | Account Closure', $string);
                }catch (\Exception $e) {
                    Log::error('close_accounts: Mail ' . $e->getMessage());
                }
            }

//            DB::commit();
        } catch (\Exception $e) {
//            DB::rollBack();
            Log::error('close_account: catch ' . $e->getMessage());
        }
        Log::info('close_account: Exit Successfully');
    }
}

function commission_distribution() {
    Log::info('commission_distribution: Begin function');
//    $rewards = Reward::whereHas('user')->where('is_paid', false)->get();
    //testing 3 day
    $rewards = Reward::
        whereHas('user')
        ->whereHas('invited_user')
        ->where('last_paid_on', '<', Carbon::now()->subHours(72))
        ->orWhereNull('last_paid_on')
        ->get();

    foreach ($rewards as $reward) {
        try {
//            DB::beginTransaction();

            if ($reward->user->preferred_payout_method == null) {
                continue;
            }

            if ($reward->user->role_id == 1) {
                continue;
            }

            if(($reward->user->preferred_payout_method == 'stripe' && $reward->user->stripe_account_id == null) || ($reward->user->preferred_payout_method == 'paypal' && $reward->user->paypal_account_details == null)) {
                continue;
            }

            //if user's account is closed or permanently deleted or invited_user account is closed
            if ($reward->user->closed_on || $reward->invited_user->closed_on) {
                continue;
            }

            //if invited_user monthly subscription not paid
            if (!has_made_monthly_payment($reward->invited_user->id)) {
                continue;
            }

//        if($reward->user->stripe_account_id) {
//        if($reward->user->preferred_payout_method == 'stripe' || $reward->user->preferred_payout_method == '') {
            if($reward->user->preferred_payout_method == 'stripe') {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET_KEY')
                );

                $balance = $stripe->balance->retrieve();

                if (($balance->available[0]->amount / 100) < $reward->amount) {
                    continue;
                }

                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                Log::info('commission_distribution | transfer: stripe acct id: '.$reward->user->stripe_account_id.' user: '.$reward->user->id.', amount: ' . $reward->amount);
                $transfer = \Stripe\Transfer::create([
                    "amount" => $reward->amount * 100,
                    "currency" => "usd",
                    "destination" => $reward->user->stripe_account_id,
                ]);

//            if ($transfer) {
                $reward->is_paid = true;
                $reward->last_paid_on = Carbon::now();
                $reward->save();

                //create reward log
                RewardLog::create(['reward_id' => $reward->id]);
//            }
            }
//            DB::commit();
        } catch (\Exception $e) {
//            DB::rollBack();
            Log::error('commission_distribution: catch ' . $e->getMessage());
        }
    }
    Log::info('commission_distribution: Exit Successfully');
}

function is_in_my_network($user_id): bool
{
    $my_network = Network::where('user_id', Auth::id())->first();

    if(!$my_network) {
        return false;
    }

    $check = NetworkMember::where('network_id', $my_network->id)->where('user_id', $user_id)->first();

    return (bool)$check;
}

function create_chat_channel($user_id, $target_id, $return = false) {
    $channel = Channel::
        orWhere(function ($q) use ($user_id, $target_id) {
            return $q->where('creator_id', $user_id)->where('participants', 'LIKE', '%'.$target_id.'%');
        })
        ->orWhere(function ($q) use ($user_id, $target_id) {
            return $q->where('creator_id', $target_id)->where('participants', 'LIKE', '%'.$user_id.'%');
        })
        ->first();

    if (is_null($channel)) {
        $channel = new Channel;
        $channel->creator_id = $user_id;
        $channel->users()->attach([$user_id, $target_id]);
        $channel->save();
    }

    if ($return) {
        return $channel;
    }
}

function get_inviter_id($user_id = null) {
    $network_member = NetworkMember::where('user_id', $user_id ?? Auth::id())->orderBy('created_at', 'ASC')->first();
    $inviters_network_id = $network_member->network_id ?? null;
    $network = Network::find($inviters_network_id) ?? null;
    return $network->user_id ?? null;
}

function referral_reversion_mail($to, $subject, $string): bool
{
//        $from = 'no-reply@tha-network.com';
    $from = 'support@thanetwork.org';

    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $html = $string;

    // Sending email
    Mail::send([], [], function ($message) use ($to, $subject, $html) {
        $message->to($to)
            ->subject($subject)
            ->setBody($html, 'text/html'); // for HTML rich messages
    });

    if (Mail::failures()) {
        return false;
    }

    return true;

//    // Sending email
//    if (mail($to, $subject, $html, $headers)) {
//        return true;
//    } else {
//        return false;
//    }
}

function account_closure_mail($to, $subject, $string): bool
{
//        $from = 'no-reply@tha-network.com';
    $from = 'support@thanetwork.org';

    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $html = $string;

    // Sending email
    Mail::send([], [], function ($message) use ($to, $subject, $html) {
        $message->to($to)
            ->subject($subject)
            ->setBody($html, 'text/html'); // for HTML rich messages
    });

    if (Mail::failures()) {
        return false;
    }

    return true;

//    // Sending email
//    if (mail($to, $subject, $html, $headers)) {
//        return true;
//    } else {
//        return false;
//    }
}

function generateBarcodeNumber() {
    $number = mt_rand(1000000000, 9999999999); // better than rand()

    // call the same function if the barcode exists already
    if (barcodeNumberExists($number)) {
        return generateBarcodeNumber();
    }

    // otherwise, it's valid and can be used
    return $number;
}

function barcodeNumberExists($number) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return User::where('invitation_code', $number)->exists();
}

function delete_deleted_accounts ()
{
    $month_ago = now()->subMonth(); // Get the date that was a month ago

    $soft_deleted_users = User::where('role_id', 2)->onlyTrashed()
        ->whereDate('deleted_at', '<', $month_ago)
        ->get();

    foreach ($soft_deleted_users as $user) {
        $user->forceDelete(); // Permanently delete the user
    }
}

function delete_closed_accounts ()
{
    $month_ago = now()->subMonth(); // Get the date that was a month ago

    $closed_users = User::where('role_id', 2)->whereNotNull('closed_on')
        ->whereDate('closed_on', '<', $month_ago)
        ->get();

    foreach ($closed_users as $user) {
        $user->forceDelete(); // Permanently delete the user
    }
}

function delete_suspended_accounts ()
{
    $month_ago = now()->subMonth(); // Get the date that was a month ago

    $suspended_users = User::where('role_id', 2)->whereNotNull('suspended_on')
        ->whereDate('suspended_on', '<', $month_ago)
        ->get();

    foreach ($suspended_users as $user) {
        $user->forceDelete(); // Permanently delete the user
    }
}

function is_user_id ($id) {
    $user_check = User::find($id);

    if ($user_check) {
        return true;
    }

    return false;
}

function toggle_user_subscription ($id = null, $pause = true, $resume = false) {
    Log::info('toggle_user_subscription: start');
    $user = get_eloquent_user($id);

    if (!$user->stripe_checkout_session_id) {
        Log::info('toggle_user_subscription: stripe_checkout_session_id not found');
        return false;
    }

    $stripe = new \Stripe\StripeClient(
        env('STRIPE_SECRET_KEY')
    );

    try {
        $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);

        if ($subscription->status == 'canceled') {
            return true;
        }

        if ($pause) {
            $pause_collection = ['behavior' => 'keep_as_draft'];
        } else if ($resume) {
            $pause_collection = '';
        }

        $stripe->subscriptions->update(
            $subscription->id,
            ['pause_collection' => $pause_collection]
        );

        Log::info('toggle_user_subscription: end');
        return true;
    } catch(\Exception $e) {
        Log::error('toggle_user_subscription ' . $e->getMessage());
        return false;
    }
}

function cancel_user_subscription ($id = null) {
    $user = get_eloquent_user($id);

    if (!$user->stripe_checkout_session_id) {
        Log::info('cancel_user_subscription: stripe_checkout_session_id not found');
        return false;
    }

    $stripe = new \Stripe\StripeClient(
        env('STRIPE_SECRET_KEY')
    );

    try {
        $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);

        if ($subscription->status == 'canceled') {
            return true;
        }

        $subscription->cancel();

        Log::info('cancel_user_subscription: end');
        return true;
    } catch(\Exception $e) {
        Log::error('cancel_user_subscription ' . $e->getMessage());
        return false;
    }
}

function smart_retries () {
    $users = get_eloquent_users();

    foreach ($users as $user) {
        if (!is_null($user->closed_on)) {
            continue;
        }

        if (is_null($user->stripe_checkout_session_id)) {
            continue;
        }

        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET_KEY')
            );

            $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);
            $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);

            if ($latest_invoice->status == "open") {
                $latest_invoice->pay();

                $payment_retries = $user->payment_retries;
                if ($latest_invoice->status == "paid") {
                    $user->payment_retries = 0;
                } else if ($latest_invoice->status == "open") {
                    $user->payment_retries = $payment_retries + 1;
                }
                $user->save();

                if ($user->payment_retries == 2) {
                    cancel_user_subscription($user->id);
                }
            }

        } catch(\Exception $e) {
            return false;
        }

        return ($latest_invoice->status == "paid");
    }
}

function np_email () {
    $users = get_eloquent_users();

    foreach ($users as $user) {
        if (!is_null($user->closed_on)) {
            continue;
        }

        if (is_null($user->stripe_checkout_session_id)) {
            continue;
        }

        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET_KEY')
            );

            $subscription = $stripe->subscriptions->retrieve($user->stripe_checkout_session_id);
            $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);

            if ($latest_invoice->status == "open") {
                $string = "Hi, we have not received your monthly membership payment.\r\n
                            Update your payment information before the 7th of the month.\r\n
                            If you do not update your payment by the 7th at 11:59 pm central time your membership will be suspended until a payment is made and you will not receive referral payments for this month.\r\n
                            Once payment is received your membership status will be updated and payments will continue in the next billing cycle.\r\n
                            If your payment is not received by 11:59 pm on the 11th of this month, you will no longer receive referral payments, your account will be closed and you will lose all of your Network Members!!!\r\n
                            Please update your account before the 11th of the month!!!!!";
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'body' => $string,
                    'sender_id' => $user->id
                ]);

                event(new PaymentNotMade($user->id, $string, 'App\Models\User', $notification->id, $user));

                //send mail to user
                $string = str_replace("\r\n", "<br />", $string);
                referral_reversion_mail($user->email, 'Tha Network Delinquency Notice', $string);
            }

        } catch(\Exception $e) {
            return false;
        }
    }
}
