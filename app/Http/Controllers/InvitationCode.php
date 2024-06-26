<?php

namespace App\Http\Controllers;

use App\Events\ReferralSent;
use App\Events\StripePayoutConnected;
use App\Helpers\WebResponses;
use App\Models\Network;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\SendInvitation;
use App\Models\User;
use App\Models\UserInvitation;
use App\Rules\EmailArray;
use App\Traits\StripePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Stripe\Stripe;

class InvitationCode extends Controller
{
    use StripePayment;

    private $amount;

    public function __construct()
    {
        //Commit on client demand
        // $this->amount = count(User::where('role_id', 2)->get()) < 5000 ? 29.99 : 59.95;
        $this->amount = 29.99;
    }

    public function showInvitationCodeForm()
    {
        return Inertia::render('Auth/SignUpInvitation');
    }

    public function sendInvitationCode(Request $request)
    {
        $data = $request->validate([
            'email' => [
                'required_if:email,in:send_code_type',
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'confirm_email' => [
                'required_if:confirm_email,in:send_code_type',
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'phone' => [
                'required_if:phone,in:send_code_type',
                'nullable',
                'string',
                'max:255'
            ],
            'send_code_type' => [
                'required',
                'in:email,phone'
            ],
        ], [
            'email.unique' => 'This user is already registered on the website.'
        ]);
        if ($request->send_code_type === 'email' && $data['email'] !== $data['confirm_email']) {
            return redirect()->back()->withErrors(['error' => 'Confirm email and email must be the same'])->withInput();
        }
        try {

//            $code = $this->generateUniqueCode();

            //get admin code
            $admin = User::where('role_id', 1)->first();
            $code = $admin ? $admin->invitation_code : $this->generateUniqueCode();

            if ($code instanceof \Exception)
                throw $code;

            DB::beginTransaction();
            $sendInvitation = SendInvitation::firstOrNew([
                'email' => $data['email']
            ]);
            $sendInvitation->phone = $data['phone'];
            $sendInvitation->save();

            $sendInvitation->invitation()->forceDelete();
            $sendInvitation->invitation()->create([
                'code' => $code
            ]);
            DB::commit();

            /* Mail::send(
                 'mails.send-invitation-code-mail',
                 ['code' => $code],
                 function ($message) use ($data) {
                     $message->to($data['email'])->subject('Invitation Code!');
                 }
             );*/

            if (!$this->mailCode($data['email'], 'Tha Network - Invitation Code!', $code))
                return WebResponses::exception("Email not send!");

            $route = route('loginForm', 'send-code=success');
            session()->put('send-code', 'success');
            return WebResponses::success(
                'Request submitted successfully!',
                null,
                $route
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponses::exception($e->getMessage());
        }
    }

    public function verifyCode(Request $request)
    {
        // check if user is logging in by invitation code
        if (User::where('invitation_code', $request->code)->exists()) {
            $inviter = User::where('invitation_code', $request->code)->first();
            session()->put('inviter_id', $inviter->id);

            $code = $this->generateUniqueCode();
            DB::beginTransaction();
            $sendInvitation = SendInvitation::firstOrNew([
                'email' => 'inviter@tha-network.com'
            ]);
            $sendInvitation->save();
            $sendInvitation->invitation()->forceDelete();
            $sendInvitation->invitation()->create([
                'code' => $code
            ]);
            DB::commit();
            session()->put('send-code', 'success');
            $userInvitation = null;
            $userInvitation = UserInvitation::where('code', $code)
                ->whereDoesntHave('payment')
                ->whereNull('deleted_at')
                ->first();
            session()->put('validate-code', $userInvitation->id);

            //cms data
            $home = Page::where('name', 'Home')->first();
            $data = json_decode($home->content ?? []);

            return Inertia::render('HowItWorks', [
                'inviter' => $inviter,
                'data' => $data
            ]);
        }

        $userInvitation = null;
        $request->validate([
            'code' => [
                'required',
                'string',
                'size:6',
                function ($attribute, $value, $fail) use (&$userInvitation) {
                    if (!$value) return;
                    $userInvitation = UserInvitation::where('code', $value)
                        ->whereDoesntHave('payment')
                        ->whereNull('deleted_at')
                        ->first();
                    if (is_null($userInvitation))
                        $fail("Invalid code!");
                },
            ],
        ]);
        try {
            session()->put('validate-code', $userInvitation->id);

            return WebResponses::success(
                null,
                null,
                //route('howItWorks')
//                route('paymentShow')
                route('work')
            );
        } catch (\Exception $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function verifyStripeCharge(Request $request)
    {
        try {
            $userSubscription = User::where('stripe_checkout_session_id', $request->stripe_subscription_id)->first();

            if ($userSubscription) {
                throw new \Exception('User is already registered with this subscription.');
            }
            $payment = Payment::where('stripe_checkout_session_id', $request->stripe_subscription_id)->first();
            if ($payment) {
                return redirect()->route('registerForm', ['stripe_checkout_session_id' => $request->stripe_subscription_id, 'customer_email' => $payment->customer_email])
                    ->with('Subscription check successfully');
            } else {
                throw new \Exception('Subscription not found.');
            }
        } catch (\Exception $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function generateUniqueCode()
    {
        try {
            do {
                $code = random_int(100000, 999999);
            } while (UserInvitation::where("code", "=", $code)->first());

            return $code;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $code
     * @return bool
     */
    public function mailCode($to, $subject, $code)
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

        // Compose a simple HTML email message
        $message = '<html><body>';
        $message .= '<h1 style="color:#f40;">Welcome to Tha Network!</h1>';
        $message .= '<p style="color:black;font-size:18px;">Please open up the link and use the invitation code given below to make an account: </p>';
        $message .= '<br />' . $code . '<br />';
        $message .= 'Link: <a href="' . route('loginForm', ['send-code' => 'success']) . '">' . route('loginForm', ['send-code' => 'success']) . '</a>';
        $message .= '</body></html>';

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
                                    <span style="display: block; margin: 20px 0 0; font-size: 18px; color: #000; font-weight: 500; text-align: center">Invitation Code: ' . $code . '</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 50%">
                                    <h6 style="font-size: 25px; margin: 30px 0 30px; text-align: center">Thanks for joining Tha Network</h6>
                                    <a href="#" style="display: table; font-size: 22px; color: green; margin: auto">Because Membership Pays</a>
                                    <span style="display: block; font-size: 20px; color: green; margin: 12px 0 0; text-align: center">$$$$$</span>
                                    <img width="398" height="398" src="' . asset('images/notifications/PaymentMade.png') . '" class="img-fluid" alt="img" style="display: table; margin: auto" />
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
            // return Mail::send([], [], function ($message) use ($to, $subject, $html) {
            Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                    ->subject($subject)
                    ->setBody($html, 'text/html'); // for HTML rich messages
            });

            return (boolean)(count(Mail::failures()) == 0);
        } catch (\Exception $e) {
            Log::error('mailCode: Email not sent: ' . $e->getMessage());
        }

        return true;

        // // Sending email
        // if (mail($to, $subject, $html, $headers)) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    public function sendInvitation(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'emails' => [
                'required',
                new EmailArray(),
//                'nullable',
//                'string',
//                'email',
//                'max:255',
//                Rule::unique('users')->whereNull('deleted_at'),
            ]
        ]);

        try {
            DB::beginTransaction();
            //register mail code if necessary
            //

            foreach ($request->emails as $email) {
//                if (!$this->invitationMailCode($email, 'Tha Network - Invitation Code!', $request->username, $request->name, Auth::user()->role_id))
                if (!invitation_mail_code($email, 'Tha Network - Invitation Code!', $request->username, $request->name, Auth::user()->role_id))
                    return WebResponses::exception("Emails not sent!");

                //Create referral
                Referral::create([
                    'user_id' => Auth::id(),
                    'email' => $email
                ]);
            }


//            $route = route('loginForm', 'send-invite=success');
            session()->put('send-invite', 'success');

            //check for user's network. create new if not created already
            $network_check = Network::where('user_id', Auth::id())->get();
            if (count($network_check) == 0) {
                Network::create([
                    'user_id' => Auth::id()
                ]);
            }

            //send referral creation notification
            $string = "Great Job! Your Referral was sent!! Keep up the good work!!! ";
            $notification = Notification::create([
                'user_id' => Auth::id(),
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => Auth::id(),
                'body' => $string,
                'sender_id' => Auth::id()
            ]);

            event(new ReferralSent(Auth::id(), $string, 'App\Models\User', $notification->id, User::with('profile')->find(Auth::id())));

            DB::commit();


            return WebResponses::success(
                'Request submitted successfully!',
                null
//                $route
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponses::exception($e->getMessage());
        }
    }

    public function join(Request $request, $username)
    {
        try {
            //cms data
            $home = Page::where('name', 'Home')->first();
            $data = json_decode($home->content ?? []);

            //get inviter
            $inviter = User::where('username', $username)->first();

//        session()->put('validate-code', '123123123');
            session()->put('inviter_id', $inviter->id);

            $code = $this->generateUniqueCode();
            DB::beginTransaction();
            $sendInvitation = SendInvitation::firstOrNew([
                'email' => 'inviter@tha-network.com'
            ]);
            $sendInvitation->save();
            $sendInvitation->invitation()->forceDelete();
            $sendInvitation->invitation()->create([
                'code' => $code
            ]);
            DB::commit();
            session()->put('send-code', 'success');
            $userInvitation = null;
            $userInvitation = UserInvitation::where('code', $code)
                ->whereDoesntHave('payment')
                ->whereNull('deleted_at')
                ->first();
            session()->put('validate-code', $userInvitation->id);

            return Inertia::render('HowItWorks', [
                'inviter' => $inviter,
                'data' => $data
            ]);
        } catch (\ErrorException $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function createStripeCheckoutSession(Request $request)
    {

        try {
            if ($request->customer_email !== $request->confirm_customer_email) {
                return back()->withErrors(['error' => 'Confirm email and email must be the same']);
            }
            $request->validate([
                'card_number' => 'required_without:token_id',
                'exp_month' => 'required_without:token_id',
                'exp_year' => 'required_without:token_id',
                'cvc' => 'required_without:token_id',
                'token_id' => 'required_without:card_number,exp_month,exp_year,cvc',
            ]);
            //if today is 1st
//            if (Carbon::today()->day == 1) {
            if (!$request->has('token_id')) {
                $charge_date = Carbon::today();
                $isMonthsFirst = true;
                $token_id = null;
            } else {
                //charge
                $stripe_charge_object = $this->stripeCharge($request, $request->token_id);
                if ($stripe_charge_object->status != 'succeeded') {
                    return Inertia::render('StripePayment', ['error' => 'Stripe charge: ' . $stripe_charge_object->status]);
                }
                session()->put('stripe_charge_object', $stripe_charge_object);

                //compute days till next month
                $currentDate = Carbon::today(); // get a new instance of the Carbon class representing today's date
                $daysTillNextMonth = $currentDate->copy()->endOfMonth()->diffInDays($currentDate, true);

                //compute charge date
                if ($daysTillNextMonth < 15) {
                    $charge_date = Carbon::today()->copy()->addMonths(2)->firstOfMonth();
                } else {
                    $charge_date = Carbon::today()->copy()->addMonth()->firstOfMonth();
                }

                $isMonthsFirst = false;
                $token_id = $request->token_id;
            }

            //subscribe
            $subscription = $this->createStripeSubscription($request, $charge_date, $isMonthsFirst, $token_id);

            if ($subscription == false) {
                if (isset($stripe_charge_object)) {
                    refund_charge($stripe_charge_object->id);
                }
                return redirect()->back()->withErrors(['error' => 'Invalid/Inactive Card provided']);
//                return Inertia::render('StripePayment', ['error' => 'Invalid/Inactive Card provided']);
            }

            //put checkout session id in session
            session()->put('stripe_checkout_session_id', $subscription->id);

            if ($subscription)
                return redirect()->route('stripeSuccessPayment', [
                    'customer_email' => $request->customer_email,
                    'customer_first_name' => $request->customer_first_name,
                    'customer_last_name' => $request->customer_last_name,
                    'customer_address' => $request->customer_address,
                ]);
        } catch (\Exception $e) {
            return Inertia::render('StripePayment', ['error' => $e->getMessage()]);
        }
    }

    public function createStripePortalSession(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET_KEY')
            );

//            $checkout_session = $stripe->checkout->sessions->retrieve(Auth::user()->stripe_checkout_session_id);
//
//            //for later
//            $subscription = $stripe->subscriptions->retrieve($checkout_session->subscription);
//            $latest_invoice = $stripe->invoices->retrieve($subscription->latest_invoice);
//            dump("Paid status: " . ($latest_invoice->status == "paid"));
//            dump(Carbon::createFromTimestamp($latest_invoice->created)->isSameDay(Carbon::today()->startOfMonth()));
//            dd($latest_invoice);
//            dd('done');

            $subscription = $stripe->subscriptions->retrieve(Auth::user()->stripe_checkout_session_id);

            // Authenticate your user.
            $session = $stripe->billingPortal->sessions->create([
                'customer' => $subscription->customer,
                'return_url' => (route('editProfileForm') . '?pmu=true'),
            ]);

            session()->put('stripe_portal_session', $session);

            $user = User::find(Auth::id());
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

            //send email (payment change) to user
            $string = "Your payment method was updated on [" . Carbon::now()->format('m-d-Y') . "],  thank you for updating your payment information.";
            send_mail($user->email, 'Tha Network | Payment Settings', $string);

//            return redirect()->route('editProfileForm');
            return redirect()->route('editProfileForm');

//        return Inertia::render('StripePayment', [
//            'checkout_session' => $checkout_session
//        ]);
        } catch (\Exception $e) {
            return redirect()->route('editProfileForm')->withErrors([$e->getMessage()]);
        }
    }

    protected function createStripeSubscription(Request $request, $charge_date, $isMonthsFirst, $token_id = null)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        //create product
        $product = $stripe->products->create([
            'name' => 'THA Network monthly subscription',
        ]);

        //create price
        $price = $stripe->prices->create([
            'unit_amount' => $this->amount * 100,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $product->id,
        ]);

        //create customer
        $customer = $stripe->customers->create([
            'name' => 'Tha network member',
        ]);
        session()->put('stripe_customer_id', $customer->id);

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

        return $subscription;
    }

    protected function stripeCharge(Request $request, $token_id)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET_KEY')
        );


//        //create customer
//        $customer = $stripe->customers->create([
//            'name' => 'Tha network member',
//        ]);
//
//        $card = $stripe->customers->createSource(
//            $customer->id,
//            [
//                'source' => [
//                    'object' => 'card',
//                    'number' => $request->card_number,
//                    'exp_month' => $request->exp_month,
//                    'exp_year' => $request->exp_year,
//                    'cvc' => $request->cvc,
//                ]
//            ]
//        );
//
//        $token = $stripe->tokens->create([
//            'card' => [
//                'number' => $request->card_number,
//                'exp_month' => $request->exp_month,
//                'exp_year' => $request->exp_year,
//                'cvc' => $request->cvc,
//            ],
//        ]);

        $charge = $stripe->charges->create([
            'amount' => $this->amount * 100,
            'currency' => 'usd',
            'source' => $token_id,
            'description' => 'Tha Network - Subscription Charge',
        ]);

        return $charge;
    }

    public function getCredentials(Request $request)
    {
        $data = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ]);
        try {
            $user = User::where('email', $request->email)->where('role_id', 2)->first();
            if (!$user) {
                return WebResponses::exception('No account exists on provided email.');
            }

            if (!send_credentials_mail($user)) {
                return WebResponses::exception('Unable to send mail.');
            }
        } catch (\Exception $e) {
            return WebResponses::exception($e->getMessage());
        }
    }

    public function showForgotPasswordForm()
    {
        return Inertia::render('Auth/ForgotPassword');
    }
}
