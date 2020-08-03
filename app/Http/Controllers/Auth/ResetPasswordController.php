<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ResetPasswordController extends Controller
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

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

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
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        //check token is expire or not
        $is_valid = $this->checkLinkExpire($request, $token);
        if ($is_valid == false) {
            \Session::flash('error', __('passwords.token'));
            return redirect()->route('login');
        }
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            \Session::flash('error', __('passwords.user'));
            return redirect()->route('login');
        }
        return view('admin.modules.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email,'is_valid'=>$is_valid,'user' => $user]
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $request_array = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at == null) {
            $request_array['status'] = 0;
        } else {
            $request_array['status'] = 1;
        }
        return $request_array;
    }

    //check link is expire or not
    protected function checkLinkExpire(Request $request, $token = null)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return true;
        }
        $value = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->tokenExists($user, $token);
        return $value;
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        //get and use of minimum charctor value and password strength from site config
        $passwordValidationRule = ['required','confirmed'];
        $passwordPolicy = getPasswordPolicy($passwordValidationRule);

        return [
            'token' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => $passwordPolicy['passwordValidationRule'],
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        //get and use of minimum charctor value and password strength from site config
        $min = 6;
        $passwordStrength = 8;

        return [
            'password.regex' => __('user.password_invalid_'.$passwordStrength.'_msg', array('min'=>$min)),
            'password.min' => __('user.password_rule_msg', array('min'=>$min))
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->resetUser($this->credentials($request));

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }
    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  \Closure  $callback
     * @return mixed
     */
    public function resetUser(array $credentials)
    {
        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        $user = $this->validateReset($credentials);

        if (! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        // $callback($user, $password);

        $this->resetPassword($user, $password);
        //user status change to 1 when activation email verify
        $user->update(['status' => 1]);

        app(\Illuminate\Auth\Passwords\PasswordBroker::class)->deleteToken($user);

        return $this->broker()::PASSWORD_RESET;
    }
    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
       
         if (is_null($user = $this->broker()->getUser($credentials))) {
            dd($credentials);
             return $this->broker()::INVALID_USER;
         }

        return $user;
    }
}
