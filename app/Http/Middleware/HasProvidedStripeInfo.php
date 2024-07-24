<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use function PHPUnit\Framework\isNull;

class HasProvidedStripeInfo
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = get_eloquent_user();
        if ((is_null($user->stripe_account_id) && is_null($user->paypal_account_details)) && $user->role_id != 1) {
            return redirect()->route('editProfileForm');
            return Inertia::render('EditProfile', [
                'user' => $user->only('name', 'email', 'created_at', 'pwh') ?? null,
                'profile' => $user->profile ?? null,
//                'profile_image' => $this->profileImg($user, 'profile_image'),
                'profile_cover' => $user->getFirstMedia('profile_cover')->original_url ?? null,
                'monthly_payment_flash' => session()->has('monthly_payment_flash') ? session()->get('monthly_payment_flash') : null,
                'has_made_monthly_payment' => has_made_monthly_payment(),
                'stripe_account_id' => $user->stripe_account_id,
                'paypal_account_details' => $user->paypal_account_details,
                'stripe_checkout_session_id' => $user->stripe_checkout_session_id,
                'stripe_portal_session' => session()->get('stripe_portal_session') ?? null,
                'has_provided_stripe_payout_information' => false,
                'preferred_payout_method' => $user->preferred_payout_method,
//            ])->with('error', 'You must Create a Stripe account or log into your Stripe account by selecting the “Create Stripe Account” button before continuing.');
            ])->with('error', 'Before continuing, please create or log into your Stripe or PayPal account below by selecting the "Create Stripe Account" or "Log into PayPal" button.');
        }

        return $next($request);
    }
}
