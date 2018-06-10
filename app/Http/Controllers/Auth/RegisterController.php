<?php

namespace App\Http\Controllers\Auth;

use App\Events\Jockey\Account\NewJockeyRegistered;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\County;
use App\Models\Jockey;
use App\Models\Nationality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::all();
        $counties = County::all();
        $nationalities = Nationality::all();

        return view('auth.register', compact('countries', 'counties', 'nationalities'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'date_of_birth' => 'required|min:10|date_format:"d/m/Y"',
            'gender' => 'required|in:male,female',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'country_id' => 'required|exists:countries,id',
            'nationality_id' => 'required|exists:nationalities,id',
            'postcode' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return Jockey::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'alias' => $data['alias'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'address_1' => $data['address_1'],
            'address_2' => $data['address_2'],
            'county_id' => $data['county_id'],
            'country_id' => $data['country_id'],
            'nationality_id' => $data['nationality_id'],
            'postcode' => $data['postcode'],
            'telephone' => $data['telephone'],
            'twitter_handle' => $data['twitter_handle'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /*
        Overwrite the registered method from the RegistersUsers trait to
        stop automatic login when jockey registers.
     */
    protected function registered(Request $request, $jockey)
    {
        // send activation email
        event(new NewJockeyRegistered($jockey));

        $this->guard()->logout();

        return redirect($this->redirectPath())
            ->with('registered', true);
    }
}
