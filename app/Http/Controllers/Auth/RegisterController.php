<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendVerifyEmail;
use App\Rules\CheckPhoneRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/';

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'string|min:10',
            'phone' => [
                'min:10',
                'max:11',
                new CheckPhoneRule(),
            ],
            'description' => 'string',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'address' => $data['address'],
            // 'phone' => $data['phone'],
            // 'description', $data['description'],
            'is_active' => 0,
            // 'avatar' => $data['avatar'],
            'verify_token' => Str::random(40),
            'role_id' => 2,
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        // dispatch(new SendVerifyEmail($user));
        $user->sendConfirmEmail();

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function verify($verify_token)
    {
        $user = User::where(['verify_token' => $verify_token])->first();
        if ($user) {
            $user->verify_token = null;
            $user->is_active = 1;
            $user->save();
        } else {
            return redirect()->route('login')->withErrors(['verify' => trans('verify email error')]);
        }
        return redirect()->route('login');
    }

    public function showResendForm()
    {
        return view('auth/resendEmail');
    }

    public function resendEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where(['email' => $request->email])->first();
        // dispatch(new SendVerifyEmail($user));
        $user->sendConfirmEmail();

        return redirect()->route('login')->withErrors(['success', trans('resend verify email success')]);
    }
}
