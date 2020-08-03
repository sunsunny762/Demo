<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use Validator;
use App\User;
use App\UserType;

class ChangePassword extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display the change password view for the existing user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('admin.modules.auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {
        $passwordValidationRule = ['required'];
        $passwordPolicy = getPasswordPolicy($passwordValidationRule);

        $messages = [
            'password-confirm.same' => __('changepassword.validation_new_confirm_password_same'),
            'new-password.regex' => __('user.password_invalid_'.$passwordPolicy['passwordStrength'].'_msg', array('min'=>$passwordPolicy['passwordMinLength']))
        ];
        $validator = Validator::make($request->all(), [
                                'current-password' => ['required',function ($attribute, $value, $fail) {
                                    if (!Hash::check($value, Auth::User()->password)) {
                                        $fail(__('changepassword.validation_invalid_current_password'));
                                    }
                                }],
                                'new-password' =>$passwordPolicy['passwordValidationRule'],
                                'password-confirm' => 'required|same:new-password',
                        ], $messages);

        if ($validator->fails()) {
            return $validator->validate();
        }

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success", __('changepassword.success'));
    }
}
