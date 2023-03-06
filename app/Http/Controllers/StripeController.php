<?php

namespace App\Http\Controllers;

use App\Events\PaypalPayoutConnected;
use App\Events\StripePayoutConnected;
use App\Helpers\WebResponses;
use App\Models\Notification;
use App\Models\User;
use App\Traits\StripePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class StripeController extends Controller
{
    use StripePayment;

    public function connectAccount(Request $request)
    {
        $user = User::find(Auth::id());
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        //if acc not connected
        if(is_null($user->stripe_account_id)) {
            $expressAccount = $stripe->accounts->create(['type' => 'express']);
            $user->stripe_account_id = $expressAccount->id;
            $user->save();
        }

        $account_link = $stripe->accountLinks->create([
            'account' => $user->stripe_account_id,
            'refresh_url' => route('editProfileForm'),
            'return_url' => route('editProfileForm'),
            'type' => 'account_onboarding',
        ]);

        //send notification to user
        $string = "You have successfully connected your Stripe account.";
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);

        event(new StripePayoutConnected($user->id, $string, 'App\Models\User', $notification->id, $user));

//        return $account_link->url;
        return WebResponses::success(
            'redirect url',
            $account_link->url
        );
    }

    public function connectPaypalAccount(Request $request)
    {
        $this->validate($request, [
            'paypal_account_details' => 'required|email'
        ]);

        $user = User::find(Auth::id());

        $user->update($request->all());

        //send notification to user
        $string = "You have successfully connected your Paypal account.";
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);

        event(new PaypalPayoutConnected($user->id, $string, 'App\Models\User', $notification->id, $user));

        return redirect()->route('editProfileForm');
    }
}
