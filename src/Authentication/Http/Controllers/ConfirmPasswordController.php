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
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
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
use App\Providers\RouteServiceProvider;

// Laravel Framework
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */



    // HEY! THE PASSWORD CONFIRM FEATURE DOES NOT WORK. ALWAYS REDIRECTED TO THE LOGIN FORM. 
    // SEE 




    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the password confirmation view.
     * 
     * OverridesIlluminate\Foundation\Auth\ConfirmsPasswords::showConfirmForm()
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmForm()
    {
       //return view('auth.passwords.confirm');
        return view('lasallesoftwarelibrary::basic.auth.passwords.confirm');
    }
}
