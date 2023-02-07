<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Payment;
use App\Models\ThaPayment;
use App\Models\User;
use App\Models\UserInvitation;
use App\Traits\StripePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HowItWorks extends Controller
{
    use StripePayment;

    private $amount;

    public function __construct()
    {
        $this->amount = count(User::where('role_id', 2)->get()) < 5000 ? 29.99 : 59.99;
        $this->middleware('is.validate.code')->except('monthlySuccessPayment');
    }

    public function show()
    {
        $home = Page::where('name', 'Home')->first();
        $data = json_decode($home->content ?? []);

        return Inertia::render('HowItWorks', [
            'data' => $data
        ]);
    }

    public function paymentShow()
    {
        $clientSecret = $this->generateClientSecret($this->amount);
        session()->put('client-secret', $clientSecret);

        return Inertia::render('Payment', [
            'client_secret' => $clientSecret
        ]);
    }

    public function stripePaymentShow()
    {
        return Inertia::render('StripePayment');
    }

    public function successPayment(Request $request)
    {
        if ($request->payment_intent_client_secret != session('client-secret'))
            return redirect()->route('loginForm');

        $userInv = UserInvitation::where('id', session('validate-code'))
            ->whereDoesntHave('payment')
            ->whereNull('deleted_at')
            ->first();

        //checking for inviter in session
        if (is_null($userInv) && !session()->has('inviter_id'))
            return redirect()->route('loginForm');

        //if registered by following invitation link
        if(session()->has('inviter_id') && is_null($userInv)) {
            $payment = Payment::create([
                'amount' => $this->amount,
                'client_secret' => session('client-secret'),
                'payable_type' => 'App\Models\Payment',
                'payable_id' => session()->get('inviter_id')
            ]);
        } else {
            $payment = $userInv->payment()->create([
                'amount' => $this->amount,
                'client_secret' => session('client-secret')
            ]);
        }

        session()->put('tha_payment_amount', $this->amount);

        return redirect()->route('registerForm');
    }

    public function stripeSuccessPayment(Request $request)
    {
//        dd(session()->has('inviter_id'));
        //checking for inviter in session
        if (!session()->has('inviter_id'))
            session()->put('validate-code', 'validate-code');
//            return redirect()->route('loginForm');

        //if registered by following invitation link
//        if(session()->has('inviter_id')) {
            $payment = Payment::create([
                'amount' => $this->amount,
                'client_secret' => session('client-secret'),
                'payable_type' => 'App\Models\Payment',
                'payable_id' => session()->has('inviter_id') ? session()->get('inviter_id') : 'registered-with-code'
            ]);
//        }

        session()->put('tha_payment_amount', $this->amount);

        return redirect()->route('registerForm');
    }

    public function monthlySuccessPayment(Request $request)
    {
        //create tha-payment log
        ThaPayment::create([
            'user_id' => Auth::id(),
            'amount' => $this->amount,
        ]);

        //remove suspension
        $user = User::find(Auth::id());
        $user->suspended_on = null;
        $user->save();

        session()->put('monthly_payment_flash', 'Your monthly subscription payment has been made!');

        return redirect()->route('editProfileForm');
    }

    private function profileImg($user, $collection)
    {
        $img = null;
        if ($user) {
            $img = $user->getFirstMedia($collection)->original_url ?? null;
        }
        return $img;
    }
}
