<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponses;
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

//        return $account_link->url;
        return redirect()->route('editProfileForm');
    }
}
