<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    protected $redirectTo = '/home';

    /*
        https://laravel-news.com/laravel-auth-redirection
        Can set where users get redirected to upon login here
     */
    // public function redirectTo()
    // {
    //     return '/@'.auth()->user()->username;
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
        Copy validateLogin method from the AuthenticatesUsers trait
        and overwrite it here to include checking that the account is active.
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => [ // username is the email field. As specified in the trait.
            'required', 'string',
            // check that it exists on the 'users' table and that active is true.
            Rule::exists('users')->where(function($query) {
                $query->where('approved', true);
            })
        ],
            'password' => 'required|string',
        ], $this->validationErrors()); // Add a custom validation error when account is not active
    }

    protected function validationErrors()
    {
        return [
            $this->username() . '.exists' => 'No account found, or your account is awaiting approval.'
        ];
    }
}
