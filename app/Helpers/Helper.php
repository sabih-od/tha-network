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
use App\Models\Post;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\RewardLog;
use App\Models\Settings;
use App\Models\ThaPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\ProductionEnvironment;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use Stripe\Stripe;
use Stripe\StripeClient;

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
        //reset goals not add
//        $user->remaining_referrals = intval($user->remaining_referrals) + intval($rank->target);
        $user->remaining_referrals = intval($rank->target);
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
                $string = "Hi,\r\n We did not receive your monthly membership payment.  Update your payment information before the 7th of the month. Once payment is received your membership status will be updated and you will continue to receive referral payments on the normal payout date.\r\n
                            If your payment is not received by 11:59 pm on the 7th of this month, you will no longer receive referral payments, your account will be closed, and you will lose all of your Network Members!!!\r\n
                            Please update your account before the 7th of the month!!!!!";
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

                //pause subscription
                toggle_user_subscription($user->id);
            }
        }
    }
}

function close_accounts() {
    Log::info('close_account: Function Start');
    $users = get_eloquent_users();
    foreach ($users as $user) {
        Log::info('user: ' . ($user->username ?? ''));
        try {
//            DB::beginTransaction();

            if (!is_null($user->closed_on)) {
                continue;
            }

            if(!has_made_monthly_payment($user->id)) {
                //close account
//            if(is_null($user->suspended_on)) {
                Log::info('closing user');
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
        Log::info('end user: ' . ($user->username ?? ''));
    }
    Log::info('close_account: Exit Successfully');
}

function commission_distribution() {
    Log::info('commission_distribution: Begin function');
//    $rewards = Reward::whereHas('user')->where('is_paid', false)->get();
    $rewards = Reward::
        whereHas('user')
        ->whereHas('invited_user')
        ->where('last_paid_on', '<', Carbon::now()->subMonth()->day(13))
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

            //if invited_user monthly subscription not paid
            if (!has_made_monthly_payment($reward->invited_user->id)) {
                //skip if reward from first payment has been rewarded
                if ($reward->is_paid == 1) {
                    continue;
                }
            }

            //if user's account is closed or permanently deleted
            if ($reward->user->closed_on) {
                continue;
            }

            //if invited_user account is closed
            if ($reward->invited_user->closed_on) {
                //skip if reward from first payment has been rewarded
                if ($reward->is_paid == 1) {
                    continue;
                }
            }

//        if($reward->user->stripe_account_id) {
//        if($reward->user->preferred_payout_method == 'stripe' || $reward->user->preferred_payout_method == '') {
            if($reward->user->preferred_payout_method == 'stripe') {
                $reward_log_check = RewardLog::where('reward_id', $reward->id)->whereDate('created_at', Carbon::today())->get();

                if (count($reward_log_check) == 0) {
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

//            if () {
                    $reward->is_paid = true;
                    $reward->last_paid_on = Carbon::now();
                    $reward->save();

                    //create reward log
                    RewardLog::create(['reward_id' => $reward->id]);
//            }
                }
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

function send_mail($to, $subject, $string): bool
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
        Log::error('cancel_user_subscription failed ' . $e->getMessage());
        return false;
    }
}

function smart_retries () {
    Log::info('smart_retries: Begin function');

    foreach (get_eloquent_users() as $user) {
        Log::info('user: ' . ($user->username ?? ''));
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
            Log::info('subscription status: ' . $subscription->status);
            Log::info('latest invoice status: ' . $latest_invoice->status);

            if ($latest_invoice->status == "draft" || $latest_invoice->status == "unpaid") {
                $stripe->invoices->finalizeInvoice($latest_invoice->id);
//                $stripe->invoices->update($latest_invoice->id, [
//                    'status' => 'open'
//                ]);
                $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
            }

            if ($latest_invoice->status == "open") {
                $latest_invoice->pay();

                $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
                if ($latest_invoice->status == "paid") {
                    $user->payment_retries = 0;
//                } else if ($latest_invoice->status == "open") {
                } else {
                    $user->payment_retries = $user->payment_retries + 1;
                }
                $user->save();
            } else {
                $user->payment_retries = $user->payment_retries + 1;
                $user->save();
            }

            if ($user->payment_retries == 3) {
                Log::info('Cancelling user subscription');
                cancel_user_subscription($user->id);
            }

        } catch(\Exception $e) {
            continue;
        }

        Log::info('end user: ' . ($user->username ?? ''));
//        return ($latest_invoice->status == "paid");
    }
    return true;
}

function np_email () {
    Log::info('np_email: Begin function');
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

            //comment if buggy
            if ($latest_invoice->status == "draft") {
                $stripe->invoices->finalizeInvoice($latest_invoice->id);
//                $stripe->invoices->update($latest_invoice->id, [ 'status' => 'open' ]);
//                $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
            }

            if ($latest_invoice->status == "open") {
                $string = "Hi,\r\n We did not receive your monthly membership payment.  Update your payment information before the 7th of the month. Once payment is received your membership status will be updated and you will continue to receive referral payments on the normal payout date.\r\n
                            If your payment is not received by 11:59 pm on the 7th of this month, you will no longer receive referral payments, your account will be closed, and you will lose all of your Network Members!!!\r\n
                            Please update your account before the 7th of the month!!!!!";
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
    Log::info('np_email: Exit Successfully');
}

function refund_charge($charge_id) {
    $stripe = new \Stripe\StripeClient(
        env('STRIPE_SECRET_KEY')
    );

    try {
        return $stripe->refunds->create([
            'charge' => $charge_id
        ]);
    } catch (\Exception $e) {
        return false;
    }
}

function get_active_subscription_ids () {
    return User::where([
        'role_id' => 2,
        'closed_on' => null
    ])->whereNotNull('stripe_checkout_session_id')->pluck('stripe_checkout_session_id');
}

function get_user_with_charge_object () {
    return User::where([
        'role_id' => 2,
        'closed_on' => null
    ])->with('profile')->whereNotNull('stripe_charge_object')->get();
}

//--------------------------API HELPERS
function profileImg($user, $collection)
{
    $img = null;
    if ($user) {
        $img = $user->getFirstMedia($collection)->original_url ?? null;
    }
    return $img;
}

function get_user_profile ($id = null, $add_stripe_information = true) {
    $user = get_eloquent_user($id);

    //check if user has linked any accounts to their stripe payout screen
    if ($add_stripe_information) {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $has_provided_stripe_payout_information = false;
        if ($user->stripe_account_id) {
            $account = $stripe->accounts->retrieve($user->stripe_account_id);
            $has_provided_stripe_payout_information = (bool)($account->external_accounts->total_count > 0);
        }
    }

    $user_obj = $user->only('id', 'name', 'email', 'created_at', 'pwh', 'role_id');
    $profile_obj = $user->profile->toArray() ?? null;
    $stripe_array = $add_stripe_information ? [ 'has_provided_stripe_payout_information' => $has_provided_stripe_payout_information ] : [];

    return array_merge($user_obj, $profile_obj, $stripe_array, [
        'profile_image' => profileImg($user, 'profile_image'),
        'profile_cover' => profileImg($user, 'profile_cover'),
        'has_made_monthly_payment' => has_made_monthly_payment($id),
        'stripe_account_id' => $user->stripe_account_id,
        'paypal_account_details' => $user->paypal_account_details,
        'stripe_checkout_session_id' => $user->stripe_checkout_session_id,
        'preferred_payout_method' => $user->preferred_payout_method,
    ]);
}

function create_user ($data) {
    $user = User::create([
        'user_invitation_id' => session('validate-code') ?? 'validate-code',
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'pwh' => $data['password'],
        'invitation_code' => generateBarcodeNumber(),
        'stripe_checkout_session_id' => $data['stripe_checkout_session_id'] ?? null,
        'stripe_customer_id' => $data['stripe_customer_id'] ?? null,
        'stripe_charge_object' => (is_string($data['stripe_charge_object']) ? $data['stripe_charge_object'] :  json_encode($data['stripe_charge_object'])) ?? null
    ]);

    $rank = get_my_rank($user->id);
    $user->remaining_referrals = intval($user->remaining_referrals) + intval($rank->target);
    $user->stripe_charge_object =  json_encode(session()->get('stripe_charge_object')) ?? null;
    $user->save();

    //create avatar based on gender
    $avatar_url = $data['gender'] == 'Male' ? public_path('images/avatars/male-avatar.png') : public_path('images/avatars/female-avatar.png');
    $user
        ->addMedia($avatar_url)
        ->preservingOriginal()
        ->toMediaCollection('profile_image');
    //create profile
    $user->profile()->create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'phone' => $data['phone'],
        'social_security_number' => $data['social_security_number'],
        'gender' => $data['gender'],
    ]);

    //notification: lets set weekly goal
    $string = "Your Weekly goals have been set. Complete your goals to get promoted to the next grade";
    $notification = Notification::create([
        'user_id' => $user->id,
        'notifiable_type' => 'App\Models\User',
        'notifiable_id' => $user->id,
        'body' => $string,
        'sender_id' => $user->id
    ]);
    event(new SetWeeklyGoal($user->id, $string, 'App\Models\User', $notification->id, $user));

    return $user;
}

function send_credentials_mail ($user) {
    $pwh = $user->pwh;

//            $from = 'no-reply@tha-network.com';
    $from = 'support@thanetwork.org';

    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $html = '<html lang="en">
                    <head>
                        <meta charset="UTF-8" />
                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        <title>The Network Membership Pays</title>
                    </head>

                    <body style="padding: 0; margin: 0" style="max-width: 1170px; margin: auto">
                        <table style="width: 1140px; margin: 2rem auto; border-spacing: 0">
                            <tr style="margin-bottom: 20px; width: 100%">
                                <a href="#"><img src="logo.png" class="img-fluid" alt="" style="display: block; max-width: 250px; margin: auto" /></a>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">
                                        Your Tha-Network Account Credentials Are Below
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">
                                        Email: '.$user->email.' | Password: '.$user->pwh.'
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="width: 50%; text-align: center">
                                    <a href="https://www.facebook.com/Tha-Network-150057600527324/" style="display: inline-block; margin: 0 6px">Facebook</a>
                                    <a href="https://twitter.com/ThaNetwork4" style="display: inline-block; margin: 0 6px">Twitter</a>
                                    <a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" style="display: inline-block; margin: 0 6px">Youtube</a>
                                    <a href="https://www.tiktok.com/@_thanetwork_?lang=en" style="display: inline-block; margin: 0 6px">Tiktok</a>
                                    <a href="https://www.instagram.com/_thanetwork_/" style="display: inline-block; margin: 0 6px">Instagram</a>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>';

    return mail($user->email, 'Forgot Password | Tha-Network', $html, $headers);
}

function get_subscription_amount () {
    return count(User::where('role_id', 2)->get()) < 5000 ? 29.99 : 59.95;
}

function stripe_charge ($token_id) {
    $stripe = new \Stripe\StripeClient(
        env('STRIPE_SECRET_KEY')
    );

    try {
        return $stripe->charges->create([
            'amount' => get_subscription_amount() * 100,
            'currency' => 'usd',
            'source' => $token_id,
            'description' => 'Tha Network - Subscription Charge',
        ]);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return json_decode(json_encode([
            "status" => $e->getMessage()
        ]));
    }
}

function stripe_subscription ($request, $charge_date, $isMonthsFirst) {
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

    //create product
    $product = $stripe->products->create([
        'name' => 'THA Network monthly subscription',
    ]);

    //create price
    $price = $stripe->prices->create([
        'unit_amount' => get_subscription_amount() * 100,
        'currency' => 'usd',
        'recurring' => ['interval' => 'month'],
        'product' => $product->id,
    ]);

    //create customer
    $customer = $stripe->customers->create([
        'name' => 'Tha network member',
    ]);

    //create payment method
    $payment_method = $stripe->paymentMethods->create([
        'type' => 'card',
        'card' => [
            'number' => $request->card_number,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
            'cvc' => $request->cvc,
        ],
    ]);

    // Retrieve payment method details
    $payment_method = $stripe->paymentMethods->retrieve($payment_method->id);

    // Check if the card is active
    $checks = $payment_method->card->checks;
    if ($checks->cvc_check == 'fail' || $checks->address_line1_check == 'fail') {
        // Return an error or handle the invalid card scenario as desired
        return false;
    }

    //attach payment method to customer
    $payment_method = $stripe->paymentMethods->attach(
        $payment_method->id,
        [
            'customer' => $customer->id
        ]
    );

    //update customer
    $customer = $stripe->customers->update(
        $customer->id,
        [
            'invoice_settings' => [
                'default_payment_method' => $payment_method->id
            ]
        ]
    );

    //create subscription
    $subscription_array['customer'] = $customer->id;
    $subscription_array['items'] = [['price' => $price->id]];
    if (!$isMonthsFirst) {
        $subscription_array['trial_end'] = strval($charge_date->timestamp);
    }

    $subscription = $stripe->subscriptions->create($subscription_array);

    return [
        'subscription' => $subscription,
        'stripe_customer_id' => $customer->id
    ];
}

function invitation_mail_code ($to, $subject, $username, $name, $role_id, $guard = 'web')
{
    $user = $guard == 'web' ? Auth::user() : auth('api')->user();

    Log::info('invitation_mail_code function start to: '.$to.', subject: '.$subject.', username: '.$username);
    $invitation_code = $user->invitation_code ? '<span style="display: block; margin: 20px 0 0; font-size: 18px; color: #000; font-weight: 500; text-align: center">Invitation Code: '.$user->invitation_code.'</span>' : '';
    $from = 'support@thanetwork.org';

    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $inviter_string = ($role_id == 1) ? '' : '<strong style="color: #ff0000;">' .$name.'</strong> invited you to join their network. ';

    $html = '<html lang="en">
                    <head>
                        <meta charset="UTF-8" />
                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        <title>The Network Membership Pays</title>
                    </head>

                    <body style="padding: 0; margin: 0" style="max-width: 1170px; margin: auto">
                        <table style="width: 1140px; margin: 2rem auto; border-spacing: 0">
                            <tr style="margin-bottom: 20px; width: 100%">
                                <a href="#"><img src="logo.png" class="img-fluid" alt="" style="display: block; max-width: 250px; margin: auto" /></a>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">
                                        Welcome to <a href="https://thanetwork.org">ThaNetwork.org</a>, '.$inviter_string.'To learn more about your Invitation click the link below or visit <a href="https://thanetwork.org">www.thanetwork.org</a> and login using the Invitation code below..
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <a href="'.route('joinByInvite', $username).'" style="font-size: 23px; color: blue; font-weight: 600; display: table; margin: auto">Invitation Link</a>
                                    '.$invitation_code.'
                                    <!-- <span style="display: block; margin: 20px 0 0; font-size: 18px; color: #000; font-weight: 500; text-align: center">Invitation Code 12345</span> -->
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <h6 style="font-size: 25px; margin: 30px 0 30px; text-align: center">Thanks for joining Tha Network</h6>
                                    <a href="#" style="display: table; font-size: 22px; color: green; margin: auto">Because Membership Pays</a>
                                    <span style="display: block; font-size: 20px; color: green; margin: 12px 0 0; text-align: center">$$$$$</span>
                                    <img width="398" height="398" src="'.asset('images/notifications/PaymentMade.png').'" class="img-fluid" alt="img" style="display: table; margin: auto" />
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <p style="color: #333; margin: 30px 0 15px; line-height: 31px; font-size: 18px; text-align: center">To learn more about ThaNetwork follow us on our Social Media Platforms</p>
                                    <!-- <p style="color: #333; margin: 10px 0; line-height: 26px">
                                        <a href="#">Invitation Link</a>
                                        Invitation Code 12345
                                    </p> -->
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%; text-align: center">
                                    <a href="https://www.facebook.com/Tha-Network-150057600527324/" style="display: inline-block; margin: 0 6px">Facebook</a>
                                    <a href="https://twitter.com/ThaNetwork4" style="display: inline-block; margin: 0 6px">Twitter</a>
                                    <a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" style="display: inline-block; margin: 0 6px">Youtube</a>
                                    <a href="https://www.tiktok.com/@_thanetwork_?lang=en" style="display: inline-block; margin: 0 6px">Tiktok</a>
                                    <a href="https://www.instagram.com/_thanetwork_/" style="display: inline-block; margin: 0 6px">Instagram</a>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>';

    // Sending email
    try {
        Mail::send([], [], function ($message) use ($to, $subject, $html) {
            $message->to($to)
                ->subject($subject)
                ->setBody($html, 'text/html'); // for HTML rich messages
        });
    } catch (\Exception $e) {
        Log::error('invitation_mail_code: Email not sent: ' . $e->getMessage());
    }

    return true;
}

function get_channel_id ($user_1_id, $user_2_id) {
    if ($channel = Channel::where('participants', 'LIKE', '%'.$user_1_id.'%')->where('participants', 'LIKE', '%'.$user_2_id.'%')->first()) {
        return $channel->id;
    }

    return null;
}

function get_post ($post_id) {
    if (!$post = Post::find($post_id)) {
        return false;
    }
    $auth_user = auth('api')->user();

    // add media in item
    $post->getMedia('post_upload');
    $files = [];
    foreach ($post->media as $media) {
        $files[] = [
            'mime_type' => $media->mime_type,
            'url' => $media->original_url,
        ];
    }
    $post->media_items = $files;

    $auth_user->attachLikeStatus($post);

    $likers = $post->likers()->latest()->simplePaginate(3);
    $r_likers = [];
    foreach ($likers as $user) {
        $r_likers[] = get_user_profile($user->id, false);
    }
    $post->recent_likes = $r_likers;

    // share post data
    if ($post->sharedPost) {
        $post->sharedPost->getMedia('post_upload');
        $s_files = [];
        foreach ($post->sharedPost->media as $media) {
            $s_files[] = [
                'mime_type' => $media->mime_type,
                'url' => $media->original_url,
            ];
        }
        $post->sharedPost->media_items = $s_files;
        // add profile image in item
        if ($post->sharedPost->user) {
            $post->sharedPost->user = get_user_profile($post->sharedPost->user->id, false);
        }
    }

    $target_user = User::find($post->user->id);

    //add author
    $post->user = get_user_profile($post->user_id, false);

    $post->is_blocked = $auth_user->isBlockedBy($target_user) || $auth_user->hasBlocked($target_user) || $target_user->isBlockedBy($auth_user) || $target_user->hasBlocked($auth_user);

    return $post;
}
