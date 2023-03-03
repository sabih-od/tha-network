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
use App\Models\Settings;
use App\Models\ThaPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use Stripe\Stripe;

function last_active($user_id): string
{
    $user = User::find($user_id);
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
        $string = "Your Weekly goals has been set. Complete your goals to get promoted to the next grade";
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

    //if trial still going on
    if ($subscription->status == 'trialing') {
        return true;
    }

    $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);

    return ($latest_invoice->status == "paid");
}

function payment_not_made() {
    $users = get_eloquent_users();
    foreach ($users as $user) {
        if(!has_made_monthly_payment($user->id)) {
            $string = "Hi, we have not received your monthly membership payment.\r\n
            Update your payment information before the 7th of the month.\r\n
            If you do not update your payment by the 7th at 11:59 pm central time your membership will be suspended for 7 days.\r\n
            You will not receive payments until you update your payment information.\r\n
            Once payment is received your membership status will be updated and payments will continue in the next billing cycle.\r\n
            If your payment is not received by 11:59 pm on the 14th day of delinquency your account will be closed and you will lose all of your Network Members!!!\r\n
            Please update your account before the 14th of the month!!!!!";
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
            referral_reversion_mail($user->email, 'Referral Reversion', $string);
        }
    }
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
    $users = get_eloquent_users();
    foreach ($users as $user) {
        if(!has_made_monthly_payment($user->id)) {
            //close account
//            if(is_null($user->suspended_on)) {
                $user->closed_on = Carbon::today();
                $user->save();
//            }

            //get what networks the user is member of
            $joined_networks_ids = NetworkMember::where('user_id', $user->id)->pluck('network_id');
            //get owners of those networks
            $joined_networks_owner_ids = Network::whereIn('id', $joined_networks_ids)->pluck('user_id');
            //send notification to owners
            foreach ($joined_networks_owner_ids as $target_id) {
                $string = $user->profile->first_name . ' ' . $user->profile->last_name . " account has been closed.";
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

            //send referral reversion notification to inviter
            $inviter_id = get_inviter_id($user->id);
            $string = "Your ".$user->profile->first_name . ' ' . $user->profile->last_name." referral is no longer a member of the network you you won’t be receiving its referral payment";
            $target = User::with('profile')->find($inviter_id);
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
    }
}

function commission_distribution() {
    $rewards = Reward::where('is_paid', false)->get();

    foreach ($rewards as $reward) {
        if($reward->user->stripe_account_id == null && $reward->user->paypal_account_details == null) {
            continue;
        }

        //if account is closed send payout to admin stripe account
        $settings = Settings::find(1);

        if ($reward->user->closed_on) {
            if (!$settings->admin_stripe_account_id) {
                continue;
            }

            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET_KEY')
            );

            $transfer = $stripe->transfers->create([
                "amount" => $reward->amount * 100,
                "currency" => "usd",
                "destination" => $settings->admin_stripe_account_id,
            ]);

            if ($transfer) {
                $reward->is_paid = true;
                $reward->save();
            }
        }

//        if($reward->user->stripe_account_id) {
        if($reward->user->preferred_payout_method == 'stripe' || $reward->user->preferred_payout_method == '') {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET_KEY')
            );

            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $transfer = \Stripe\Transfer::create([
                "amount" => $reward->amount * 100,
                "currency" => "usd",
                "destination" => $reward->user->stripe_account_id,
            ]);

            if ($transfer) {
                $reward->is_paid = true;
                $reward->save();
            }
        }

//        else if($reward->user->paypal_account_details) {
        else if($reward->user->preferred_payout_method == 'paypal') {
            $clientId = env('PAYPAL_CLIENT_ID');
            $clientSecret = env('PAYPAL_SECRET_KEY');


            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);
            $request = new PayoutsPostRequest();
            $body = json_decode(
                '{
                "sender_batch_header":
                {
                  "email_subject": "SDK payouts test txn"
                },
                "items": [
                {
                  "recipient_type": "EMAIL",
                  "receiver": "'.$reward->user->paypal_account_details.'",
                  "note": "Your payout",
                  "sender_item_id": "Test_txn_12",
                  "amount":
                  {
                    "currency": "USD",
                    "value": "'.($reward->amount).'"
                  }
                }]
              }',
                true);
            $request->body = $body;
//            $client = PayPalClient::client();
            $response = $client->execute($request);

            if ($response && $response->statusCode && $response->statusCode == 201) {
                $reward->is_paid = true;
                $reward->save();
            }
        }
    }
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

function create_chat_channel($user_id, $target_id) {
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
}

function get_inviter_id($user_id = null) {
    $network_member = NetworkMember::where('user_id', $user_id ?? Auth::id())->orderBy('created_at', 'ASC')->first();
    $inviters_network_id = $network_member->network_id ?? null;
    $network = Network::find($inviters_network_id) ?? null;
    return $network->user_id ?? null;
}

function referral_reversion_mail($to, $subject, $string): bool
{
    $from = 'no-reply@tha-network.com';

    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $html = $string;

    // Sending email
    if (mail($to, $subject, $html, $headers)) {
        return true;
    } else {
        return false;
    }
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
