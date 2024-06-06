<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Payment;
use App\Models\ThaPayment;
use App\Models\User;
use App\Models\UserInvitation;
use App\Traits\StripePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class HowItWorks extends Controller
{
    use StripePayment;

    private $amount;

    public function __construct()
    {
        //Commit on client demand
        //$this->amount = count(User::where('role_id', 2)->get()) < 5000 ? 29.99 : 59.99;
        $this->amount = 29.99;
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
        return Inertia::render('StripePayment', [
            'isMonthsFirst' => (Carbon::today()->day == 1)
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

        //checking for inviter in session
        if (is_null($userInv) && !session()->has('inviter_id'))
            return redirect()->route('loginForm');

        //if registered by following invitation link
        if (session()->has('inviter_id') && is_null($userInv)) {
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
            'payable_id' => session()->has('inviter_id') ? session()->get('inviter_id') : 'registered-with-code',
            'stripe_checkout_session_id' => session()->has('stripe_checkout_session_id') ? session()->get('stripe_checkout_session_id') : null,
            'stripe_charge_object' => session()->has('stripe_charge_object') ? json_encode(session()->get('stripe_charge_object')) : null,
            'stripe_customer_id' => session()->has('stripe_customer_id') ? session()->get('stripe_customer_id') : null,
            'customer_email' => isset($request->customer_email) ? $request->customer_email : null
        ]);

//        }

        session()->put('tha_payment_amount', $this->amount);

        $this->sendPaymentSuccessfullEmail($request->customer_email,
            session()->has('stripe_checkout_session_id') ? session()->get('stripe_checkout_session_id') : null);

        return redirect()->route('registerForm', [
            'customer_email' => $request->customer_email,
            'customer_first_name' => $request->customer_first_name,
            'customer_last_name' => $request->customer_last_name,
            'customer_address' => $request->customer_address,
        ]);
    }

    public function sendPaymentSuccessfullEmail($to, $subscription_id)
    {
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
                        <title>ThaNetwork.org / Thanks for Payment</title>
                    </head>

                    <body style="padding: 0; margin: 0" style="max-width: 1170px; margin: auto">
                        <table style="width: 1140px; margin: 2rem auto; border-spacing: 0">
                            <tr style="margin-bottom: 20px; width: 100%">
                                <a href="#"><img src="logo.png" class="img-fluid" alt="" style="display: block; max-width: 250px; margin: auto" /></a>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">
                                       Congratulations! Your payment to ThaNetwork.org has been successfully processed.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <h6 style="font-size: 25px; margin: 30px 0 30px; text-align: center">Thanks for joining Tha Network</h6>

                                    <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">
                                    In the event that you encounter any issues while creating your profile page or if
                                     you accidentally close the tab and are unable to continue creating your account,
                                      please utilize the following Stripe ID to resume the process:</p>

                                    <span style="display: block; font-size: 20px; color: green; margin: 12px 0 0; text-align: center">Subscription ID :  ' . $subscription_id . '</span>

                                      <p style="color: #333; margin: 0 0 30px; line-height: 31px; font-size: 18px; text-align: center">This unique identifier will help you seamlessly continue where you left off and
                                       successfully complete the account creation process.</p>
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
            Mail::send([], [], function ($message) use ($to, $html) {
                $message->to($to)
                    ->subject('Subscription Successful')
                    ->setBody($html, 'text/html'); // for HTML rich messages
            });

            return (count(Mail::failures()) < 1);
        } catch (\Exception $e) {
            return false;
        }


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
