<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WebResponses;
use App\Http\Controllers\Controller;
use App\Models\SendInvitation;
use App\Models\UserInvitation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
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
        if ($userInv || session()->has('inviter_id'))
            return Inertia::render('Auth/Register');
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
                    if (!$value) return;
                    $userInvitation = UserInvitation::where('id', $value)
                        ->whereHas('payment')
                        ->whereNull('deleted_at')
                        ->exists();
                    if (!$userInvitation) {
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

        $user->profile()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'social_security_number' => $data['social_security_number'],
        ]);

        return $user;
    }
}
