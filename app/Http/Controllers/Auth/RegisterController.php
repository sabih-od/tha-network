<?php

namespace App\Http\Controllers\Auth;

use App\Events\AfterRegistrationAppPromotion;
use App\Events\NewMemberSignup;
use App\Events\RankPromoted;
use App\Events\ReferralCompleted;
use App\Events\SetWeeklyGoal;
use App\Helpers\WebResponses;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\SendInvitation;
use App\Models\ThaPayment;
use App\Models\UserInvitation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('is.validate.code');
    }

    public function showRegistrationForm(Request $request)
    {
        $userInv = UserInvitation::where('id', session('validate-code'))
            ->whereHas('payment')
            ->whereNull('deleted_at')
            ->exists();
        if ($userInv) {
            return Inertia::render('Auth/Register', [
                'stripe_checkout_session_id' => $request->stripe_checkout_session_id,
                'customer_email' => $request->customer_email,
            ]);
        } //checking for inviter info as well
        else if (session()->has('inviter_id')) {
            return Inertia::render('Auth/Register', [
                'inviter_id' => session()->get('inviter_id'),
                'email' => 'asd',
                'stripe_checkout_session_id' => $request->stripe_checkout_session_id,
                'customer_email' => $request->customer_email,
            ]);
        } else if (session()->has('validate-code') && session()->get('validate-code') == 'validate-code') {
            return Inertia::render('Auth/Register', [
                'stripe_checkout_session_id' => $request->stripe_checkout_session_id,
                'customer_email' => $request->customer_email,
            ]);
        }
        else
            return redirect(route('loginForm'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['user_invitation_id'] = session('validate-code');
        return Validator::make($data, [
            'user_invitation_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$value || !(session()->has('inviter_id'))) return;
                    $userInvitation = UserInvitation::where('id', $value)
                        ->whereHas('payment')
                        ->whereNull('deleted_at')
                        ->exists();
                    if (!$userInvitation && !(session()->has('inviter_id'))) {
                        session()->flush();
                        $fail("Invalid invitation id!");
                    }
                }
            ],
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255'
            ],
            'gender' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'nullable',
                'max:255'
            ],
            'username' => [
                'required',
                'regex:/\w*$/',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'confirmed'
            ],
            'social_security_number' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {
        return create_user($data);
//        $user = User::create([
//            'user_invitation_id' => session('validate-code'),
//            'username' => $data['username'],
//            'email' => $data['email'],
//            'password' => Hash::make($data['password']),
//            'pwh' => $data['password'],
//            'invitation_code' => generateBarcodeNumber(),
//            'stripe_checkout_session_id' => $data['stripe_checkout_session_id'] ?? null,
//            'stripe_customer_id' => $data['stripe_customer_id'] ?? null
//        ]);
//
//        $rank = get_my_rank($user->id);
//        $user->remaining_referrals = intval($user->remaining_referrals) + intval($rank->target);
//        $user->stripe_charge_object =  json_encode(session()->get('stripe_charge_object')) ?? null;
//        $user->save();
//
//        //create avatar based on gender
//        $avatar_url = $data['gender'] == 'Male' ? public_path('images/avatars/male-avatar.png') : public_path('images/avatars/female-avatar.png');
//        $user
//            ->addMedia($avatar_url)
//            ->preservingOriginal()
//            ->toMediaCollection('profile_image');
//        //create profile
//        $user->profile()->create([
//            'first_name' => $data['first_name'],
//            'last_name' => $data['last_name'],
//            'phone' => $data['phone'],
//            'social_security_number' => $data['social_security_number'],
//            'gender' => $data['gender'],
//        ]);
//
//        //notification: lets set weekly goal
//        $string = "Your Weekly goals have been set. Complete your goals to get promoted to the next grade";
//        $notification = Notification::create([
//            'user_id' => $user->id,
//            'notifiable_type' => 'App\Models\User',
//            'notifiable_id' => $user->id,
//            'body' => $string,
//            'sender_id' => $user->id
//        ]);
//        event(new SetWeeklyGoal($user->id, $string, 'App\Models\User', $notification->id, $user));
//
//        return $user;
    }

//    override function
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $req = $request->all();
        if ($req['email'] !== $req['confirm_email']) {
           return redirect()->back()->with('error', 'confirm_email and email must be same');
        }

            if (isset($req['stripe_subscription_id'])){
                $checkPayment = Payment::where('stripe_checkout_session_id', $req['stripe_subscription_id'])->first();
                    $req['stripe_checkout_session_id'] = $checkPayment->stripe_checkout_session_id;
                $req['stripe_customer_id'] = $checkPayment->stripe_customer_id;
                $req['stripe_charge_object'] = $checkPayment->stripe_charge_object;
            }
          else{
                if(session()->has('stripe_checkout_session_id')) {
                    $req['stripe_checkout_session_id'] = session()->get('stripe_checkout_session_id');
                }
                if(session()->has('stripe_customer_id')) {
                    $req['stripe_customer_id'] = session()->get('stripe_customer_id');
                }
            }

        event(new Registered($user = $this->create($req)));

        //if user was invited by link: add to their friend list
        if(session()->has('inviter_id')) {
            $inviter_id = session()->get('inviter_id');
            $check = User::with('profile')->where('id', $inviter_id)->get();
            if(count($check) > 0) {
                $inviter = User::with('profile', 'network')->find($inviter_id);
                $user = User::with('profile')->find($user->id);

                //create chat channel for both
                create_chat_channel($user->id, $inviter->id);

                //check for inviter's network. create new if not created already
                $network_check = Network::where('user_id', $inviter->id)->get();
                if(count($network_check) == 0) {
                    $inviter_network = Network::create([
                        'user_id' => $inviter->id
                    ]);
                } else {
                    $inviter_network = Network::where('user_id', $inviter->id)->first();
                }

                //add to inviters network
                NetworkMember::create([
                    'user_id' =>  $user->id,
                    'network_id' => $inviter_network->id,
                ]);

                //add inviter to user's network
                $new_network = Network::create([
                    'user_id' => $user->id
                ]);
                NetworkMember::create([
                    'user_id' =>  $inviter->id,
                    'network_id' => $new_network->id,
                ]);

                //complete referral if present
                $referral = Referral::with('user')->where([
                    'user_id' => $inviter_id,
                    'email' => $request->email,
                    'status' => false,
                ])->first();

                if(!$referral) {
                    $referral = Referral::create([
                        'user_id' => $inviter_id,
                        'email' => $request->email,
                        'status' => false,
                    ]);
                }
                $referral->update(['status' => true]);

                //rank check
                $prev_rank = get_my_rank($inviter->id);
                //subtract from user's remaining referrals
                $inviter->remaining_referrals = $inviter->remaining_referrals - 1;
                $inviter->save();
                $new_rank = get_my_rank($inviter->id);

                //notification if rank changed
                if($new_rank->target > $prev_rank->target || $new_rank->target != $prev_rank->target) {
                    $string = "Congratulations, you've been promoted to the next rank keep up the good work!!!";
                    $notification = Notification::create([
                        'user_id' => $inviter->id,
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $inviter->id,
                        'body' => $string,
                        'sender_id' => $inviter->id
                    ]);
                    event(new RankPromoted($inviter->id, $string, 'App\Models\User', $notification->id, $inviter));
                }

                //send referral completion notification
                $string = $request->first_name . ' ' . $request->last_name . " just joined your network!! Congratulations, Keep up the good work!!!";
                $notification = Notification::create([
                    'user_id' => $inviter->id,
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $inviter->id,
                    'body' => $string,
                    'sender_id' => $inviter->id
                ]);

                event(new ReferralCompleted($inviter->id, $string, 'App\Models\User', $notification->id, $inviter));

//                //subtract from user's remaining referrals
//                $inviter->remaining_referrals = $inviter->remaining_referrals - 1;
//                $inviter->save();
                //create payout log

                if ($inviter->role_id == 2){
                    Reward::create([
                        'user_id' => $inviter->id,
                        'amount' => session()->get('tha_payment_amount') == 29.99 ? 10.00 : 39.99,
                        'on_inviting' => $user->id
                    ]);
                }

                session()->remove('inviter_id');
            }
        }

        //notification(s) after registration
        $string = "Welcome To Tha Network Let’s get to work sending Referrals, but first Let’s Create a Profile Page!!";
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);
        event(new NewMemberSignup($user->id, $string, 'App\Models\User', $notification->id, User::with('profile')->find($user->id)));
//        //notification(s) after registration
//        $string = "Now that you are a member and have completed setting up your account, please go to your App store and download the APP!! Let’s get started making some CASH!!!";
//        $notification = Notification::create([
//            'user_id' => $user->id,
//            'notifiable_type' => 'App\Models\User',
//            'notifiable_id' => $user->id,
//            'body' => $string,
//            'sender_id' => $user->id
//        ]);
//        event(new AfterRegistrationAppPromotion($user->id, $string, 'App\Models\User', $notification->id, User::with('profile')->find($user->id)));

        //welcome email
        $this->sendWelcomeEmail($user->email);

        //create tha-payment log
        ThaPayment::create([
            'user_id' => $user->id,
            'amount' => session()->get('tha_payment_amount'),
        ]);
        session()->remove('tha_payment_amount');

        $this->guard()->login($user);

        return redirect()->route('editProfileForm');

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    public function sendWelcomeEmail($to)
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
                                        Welcome to <a href="https://thanetwork.org">ThaNetwork.org</a>, You may now enter the site and start earning CASH!!
                                    </p>
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
            Mail::send([], [], function ($message) use ($to, $html) {
                $message->to($to)
                    ->subject('Welcome To Tha Network!')
                    ->setBody($html, 'text/html'); // for HTML rich messages
            });

            return (count(Mail::failures()) < 1);
        } catch (\Exception $e) {
            return false;
        }

        // if (mail($to, $subject, $html, $headers)) {
        //     return true;
        // } else {
        //     return false;
        // }
    }
}
