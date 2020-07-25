<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Cart;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */


    use AuthenticatesUsers;


    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     */
    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }


    /**
     * The user has been authenticated.
     */
    protected function authenticated()
    {
        if (Auth::user()->status == "0") {
            Auth::logout();
        } else {
            if (isset(session()->get('url')['intended'])) {
                $this->redirectTo = session()->get('url')['intended'];
            } elseif (count(\Cart::getContent())) {
                $this->redirectTo = "order/checkout";
            } else {
                if (Auth::user()->roles[0]->name == "super-admin") {
                    $this->redirectTo = "/admin/dashboard";
                } else {
                    $this->redirectTo = "/page/my-account";
                }
            }
        }
    }
}
