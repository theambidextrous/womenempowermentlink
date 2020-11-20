<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Config;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\NewSignUp;

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
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo(){
        
        if( Auth::user()->is_admin ){
           return route('a_home');
        }
        elseif( Auth::user()->is_teacher ){
            return route('t_home');
        }
        elseif( Auth::user()->is_student ){
            return route('s_home');
        }
        else{
            return route('login');
        }
    }

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:13', 'unique:users'],
            'gender' => ['required', 'string', 'max:6', 'not_in:nn'],
            'position' => ['required', 'string'],
            'county' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        if( User::where('email', $data['email'])->count() > 0 ){
            return view('auth.register')->with(['error' => 'Email already in use.']);
        }
        if( User::where('phone', $data['phone'])->count() > 0 ){
            return view('auth.register')->with(['error' => 'phone already in use.']);
        }
        
        $user_create = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'position' => $data['position'],
            'county' => $data['county'],
            'is_admin' => false,
            'is_teacher' => false,
            'is_student' => true,
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);
        Mail::to($data['email'])->send(new NewSignUp($data));
        return $user_create;
    }
    protected function trans_no()
    {
        $length = 24;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $arr = str_split($randomString, 6);
        $rtn = $arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3];
        return $rtn;
    }
}
