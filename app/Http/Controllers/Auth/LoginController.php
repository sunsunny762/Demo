<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Cache\Repository as Cache;

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
    protected $redirectTo = 'admin/dashboard';

    /**
     * Max invalid login attemps count
     *
     * @var int
     */
    protected $maxAttempts = 10;

    protected $decayMinutes = 1; // Default is 1

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->maxAttempts = 5;
        $this->decayMinutes = 2;
        $this->middleware('guest')->except('logout');
        $this->cache = $cache;
    }

    /**
     * Change Login Form Template.
     *
     * Overwrite Illuminate\Foundation\Auth\AuthenticatesUsers Namespace's showLoginForm function.
     *
     * @return view
     *
     */
    public function showLoginForm()
    {
        return view('admin.modules.auth.login');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [$this->username() => $request->{$this->username()}, 'password' => $request->password, 'status' => 1];
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }


    /**
      * Send the response after the user was authenticated.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        //remove reset Passowrd token
        $user = $this->guard()->user();
        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->deleteToken($user);

        return $this->authenticated($request, $user)
                ?: redirect()->intended($this->redirectPath());
    }

    /* Method override to send correct error messages
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withErrors([
                'auth_failed' => __('auth.failed')
            ]);
    }

    /**
    * Redirect the user after determining they are locked out.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    protected function sendLockoutResponse(Request $request)
    {
        $strKey = $this->throttleKey($request); // Get current user key
        $intSeconds = $this->decayMinutes * 60; // Get block time and convert it to seconds
        $intAttempts = $this->limiter()->attempts($strKey); // Get current number of attempts
        if ($intAttempts == $this->maxAttempts) {
            $this->cache->forget($strKey . ':timer'); // Reset block time for seting new block time
            $this->limiter()->hit($strKey, $intSeconds); // Set new block time define in our system
        }

        $seconds = $this->limiter()->availableIn(
            $strKey
        );

        return redirect()->back()
            ->withErrors([
                'auth_throttle' => __('auth.throttle', ['seconds' => $seconds])
            ]);
    }
}
