<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMemberSignup;
use App\Events\RankPromoted;
use App\Events\ReferralCompleted;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\NetworkMember;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\ThaPayment;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api2', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            ($request->has('email') ? 'email' : 'username') => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
                'errors' => $validator->errors()
            ], 401);
        }

        $credentials = $request->has('email') ? $request->only(['email', 'password']) : $request->only(['username', 'password']);

//        if (!$token = auth('api')->attempt($credentials)) {
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $resp = get_user_profile(auth('api')->user()->id);
        $resp['token'] = $token;


        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully!',
            'data' => $resp,
        ]);
    }

    public function register (Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'invitation_code' => 'required|string',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'phone' => 'nullable|max:255',
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
                'social_security_number' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bad Request',
                    'errors' => $validator->errors()
                ], 401);
            }

            event(new Registered($user = create_user($request->all())));

            if ($inviter = User::with('profile', 'network')->where('invitation_code', $request->invitation_code)->first()) {
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
                    'user_id' => $inviter->id,
                    'email' => $request->email,
                    'status' => false,
                ])->first();

                if(!$referral) {
                    $referral = Referral::create([
                        'user_id' => $inviter->id,
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

                //create payout log
                Reward::create([
                    'user_id' => $inviter->id,
                    'amount' => 10.00,
                    'on_inviting' => $user->id
                ]);
            } else {
                //add inviter to user's network
//                $new_network = Network::create([
                Network::create([
                    'user_id' => $user->id
                ]);
            }

            $string = "Welcome To Tha Network Let’s get to work sending Referrals, but first Let’s Create a Profile Page!!";
            $notification = Notification::create([
                'user_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'body' => $string,
                'sender_id' => $user->id
            ]);
            event(new NewMemberSignup($user->id, $string, 'App\Models\User', $notification->id, User::with('profile')->find($user->id)));

            //welcome email
            $this->sendWelcomeEmail($user->email);

            //create tha-payment log
            ThaPayment::create([
                'user_id' => $user->id,
                'amount' => 29.99,
            ]);

            DB::commit();
            return $this->login($request);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('API FAILED (/auth/register) | Error: ' . $e->getMessage());


            return response()->json([
                'success' => false,
                'message' => 'Registration Failed.',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $resp = get_user_profile(auth('api')->user()->id ?? null);


        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $resp,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth(Auth::guard()->getName())->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
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
    }

}
