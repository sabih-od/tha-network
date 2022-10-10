<?php

namespace App\Http\Controllers\Auth;

use App\Events\AfterRegistrationAppPromotion;
use App\Events\ReferralCompleted;
use App\Helpers\WebResponses;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\SendInvitation;
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

    public function showRegistrationForm()
    {
        $userInv = UserInvitation::where('id', session('validate-code'))
            ->whereHas('payment')
            ->whereNull('deleted_at')
            ->exists();
        if ($userInv) {
            return Inertia::render('Auth/Register');
        }
        //checking for inviter info as well
        else if (session()->has('inviter_id')) {
            return Inertia::render('Auth/Register', [
                'inviter_id' => session()->get('inviter_id'),
                'email' => 'asd'
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
                    if (!$userInvitation || !(session()->has('inviter_id'))) {
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
            'phone' => [
                'required',
                'string',
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
                Password::min(8)/*->mixedCase()*/,
                'confirmed'
            ],
            'social_security_number' => [
                'required',
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
        $user = User::create([
            'user_invitation_id' => session('validate-code'),
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $rank = get_my_rank($user->id);
        $user->remaining_referrals = intval($user->remaining_referrals) + intval($rank->target);
        $user->save();

        $user->profile()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'social_security_number' => $data['social_security_number'],
        ]);

        return $user;
    }

//    override function
    public function register(Request $request)
    {
//        return User::find(session()->get('inviter_id'));
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //if user was invited by link: add to their friend list
        if(session()->has('inviter_id')) {
            $inviter_id = session()->get('inviter_id');
            $check = User::with('profile')->where('id', $inviter_id)->get();
            if(count($check) > 0) {
//                $inviter = $check[0];
                $inviter = User::with('profile')->find($inviter_id);
                $user = User::with('profile')->find($user->id);


                //$user->follow($inviter);

                //add to inviters network
                NetworkMember::create([
                    'user_id' =>  $user->id,
                    'network_id' => $inviter->network->id,
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
                if($referral) {
                    $referral->update(['status' => true]);
                    $inviter->remaining_referrals = $inviter->remaining_referrals - 1;
                    $inviter->save();

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
                }

                //subtract from user's remaining referrals
                $referral->user->remaining_referrals = $referral->user->remaining_referrals - 1;
                $referral->user->save();

                session()->remove('inviter_id');
            }
        }

        //notification(s) after registration
        $string = "Now that you are a member and have completed setting up your account, please go to your App store and download the APP!! Let’s get started making some CASH!!!";
        $notification = Notification::create([
            'user_id' => $user->id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'body' => $string,
            'sender_id' => $user->id
        ]);
        event(new AfterRegistrationAppPromotion($user->id, $string, 'App\Models\User', $notification->id, User::with('profile')->find($user->id)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
}
