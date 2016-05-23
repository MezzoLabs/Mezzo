<?php


namespace MezzoLabs\Mezzo\Cockpit\Http\Controllers\Auth;


use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MezzoLabs\Mezzo\Cockpit\Http\Controllers\NonModuleController;

class AuthController extends NonModuleController
{
    protected $loginPath = "mezzo/auth/login";

    protected $redirectAfterLogout = "mezzo/auth/login";

    protected $redirectTo = "mezzo/";

    use AuthenticatesUsers, RegistersUsers, ThrottlesLogins {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
        RegistersUsers::postRegister as traitPostRegister;
    }

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('cockpit::auth.login');
    }

    public function getRegister()
    {
        return view('cockpit::auth.register');
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        User::pausePermissions();

        $this->traitPostRegister($request);
    }
}