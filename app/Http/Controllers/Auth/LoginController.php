<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('email');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    public function showLoginForm()
    {
        $maintenance_banner = Page::where('slug', 'maintenance-banner')->first();
        $data = [];
        if ($maintenance_banner && $maintenance_banner->content) {
            $data = json_decode($maintenance_banner->content);
        }
        return Inertia::render('Auth/Login', ['maintenance_banner' => $data]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role_id == 1) {
            return redirect()->route('dashboard');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /*public function login(Request $request)
    {
        $this->validateLogin($request);

        return Redirect::route('dashboard');
    }*/
}
