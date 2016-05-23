<?php

namespace MezzoLabs\Mezzo\Cockpit\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use MezzoLabs\Mezzo\Http\Exceptions\NoPermissionsException;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\UserRepository;
use Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
        postEmail as defaultPostEmail;
    }
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * Create a new password controller instance.
     */
    public function __construct(UserRepository $users)
    {
        $this->middleware('guest');
        $this->users = $users;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('cockpit::auth.password');
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('cockpit::auth.reset')->with('token', $token);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $user = $this->users->findByEmail($request->get('email'));

        if($user && !$user->canSeeCockpit()){
            throw new NoPermissionsException('You are not allowed to see the cockpit.');
        }

        return $this->defaultPostEmail($request);
    }
}
