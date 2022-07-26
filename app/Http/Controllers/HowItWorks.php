<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use App\Traits\StripePayment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HowItWorks extends Controller
{
    use StripePayment;

    private $amount = 29.99;

    public function __construct()
    {
        $this->middleware('is.validate.code');
    }

    public function show()
    {
        return Inertia::render('HowItWorks');
    }

    public function paymentShow()
    {
        $clientSecret = $this->generateClientSecret($this->amount);
        session()->put('client-secret', $clientSecret);

        return Inertia::render('Payment', [
            'client_secret' => $clientSecret
        ]);
    }

    public function successPayment(Request $request)
    {
        if ($request->payment_intent_client_secret != session('client-secret'))
            return redirect()->route('loginForm');

        $userInv = UserInvitation::where('id', session('validate-code'))
            ->whereDoesntHave('payment')
            ->whereNull('deleted_at')
            ->first();
        if (is_null($userInv))
            return redirect()->route('loginForm');

        $payment = $userInv->payment()->create([
            'amount' => $this->amount,
            'client_secret' => session('client-secret')
        ]);

        return redirect()->route('registerForm');
    }


}
