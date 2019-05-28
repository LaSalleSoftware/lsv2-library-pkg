<?php

/**
 * This file is part of the Lasalle Software library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-library-pkg
 *
 */

namespace Lasallesoftware\Library\Authentication\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Library\Common\Http\Controllers\CommonController;
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel Framework
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Class LoginController
 *
 * @package Lasallesoftware\Library\Authentication\Http\Controllers
 */
class LoginController extends CommonController
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
    //protected $redirectTo = '/home';
    protected $redirectTo = '/nova';

    /**
     * The UuidGenerator instance
     *
     * @var Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->middleware('guest')->except('logout');

        //$this->middleware('Nova.guest:'.config('Nova.guard'))->except('logout');

        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Show the application's login form.
     *
     * Overrides Illuminate\Foundation\Auth\AuthenticatesUsers::showLoginForm()
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('lasallesoftwarelibrary::basic.auth.login');
    }

    public function login(Request $request)
    {
        // first thing is to create a uuid
        $this->uuidGenerator->createUuid(4, "from LoginControler::login()");

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * From the Nova Laravel\Nova\Http\Controllers\LoginController override of AuthenticatesUsers::logout()
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($this->redirectPath());
    }
}
