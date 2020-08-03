<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Events\BulkAction;
use App\Http\Controllers\Controller;
use App\Notifications\UserVerification;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = new User;
        $users = $users->getResult($request);
        // Render view
        return view('admin.modules.user.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('admin.modules.user.addedit')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $request = $this->stripHtmlTags($request, User::$notStripTags);
        $rules = [
            'email' => ['required', 'email:rfc,dns', Rule::unique('users')->whereNull('deleted_at')],
            'first_name' => 'required',
        ];
        $this->validate($request, $rules);

        $user = User::withTrashed()->where('email', $request->get('email'))->first();

        if (!empty($user->id)) {
            // Restore user account if already exist
            $user->restore();

            // Update user data.
            $data = $request->all();
            $data['deleted_at'] = null;
            $data['password'] = null;
            $data['email_verified_at'] = null;
            $data['status'] = 0;
            $data['user_type_id'] = 2;
            $user->fill($data);
            $user->save();
            $message = 'user.restored_success';
        } else {
            // Save the User Data
            $user = new User;
            $user->fill($request->all());
            $user->save();
            $message = 'user.create_success';
        }
       
        // Generate Reset Password Token
        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);

        // Send Verification Email to User.
        $user->notify(new UserVerification($token));

        if ($request->get('btnsave') == 'savecontinue') {
            return redirect()->route('user.edit', ['id' => $user->id])->with("success", __($message, ['first_name' => $request->get('first_name'), 'last_name' => $request->get('last_name')]));
        } elseif ($request->get('btnsave') == 'save') {
            return redirect()->route('user.index')->with("success", __($message, ['first_name' => $request->get('first_name'), 'last_name' => $request->get('last_name')]));
        } else {
            return redirect()->route('user.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.modules.user.addedit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request = $this->stripHtmlTags($request, User::$notStripTags);
        $id = $user->id;
        $rules = [
            'email' => "required|email:rfc,dns|unique:users,email,{$id},id,deleted_at,NULL",
            'first_name' => 'required'
        ];
        $this->validate($request, $rules);
        $data = $request->all();
        unset($data['email']);
        $validate = 'yes';
        if ($user->email_verified_at == null) {
            if (isset($data['status']) && !empty($data['status']) &&  $data['status'] == 1) {
                $validate = 'not';
            }
            unset($data['status']);
        }
        if ($validate == 'not') {
            \Session::flash('success', __('user.update_status_not_valid', ['first_name' => $request->get('first_name'), 'last_name' => $request->get('last_name')]));
        } else {
            \Session::flash('success', __('user.update_success', ['first_name' => $request->get('first_name'), 'last_name' => $request->get('last_name')]));
        }
        // Save the User Data
        $user->fill($data);
        $user->save();

        if ($request->get('btnsave') == 'savecontinue') {
            return redirect()->back();
        } elseif ($request->get('btnsave') == 'save') {
            return redirect()->route('user.index');
        } else {
            return redirect()->route('user.index');
        }
    }

    /**
     * Send reset password email to user
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($email)
    {
        $credentials = ['email' => $email];

        $user = User::where('email', $email)->first();

        if ($user->email_verified_at == null) {
            // Generate Reset Password Token
            $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);

            // Send Verification Email to User.
            $user->notify(new UserVerification($token));
            return redirect()->back()->with('success', __('user.activationlink_success', ['email' => $email]));
        } else {
            $response = Password::sendResetLink($credentials, function (Message $message) {
                $message->subject($this->getEmailSubject());
            });

            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return redirect()->back()->with('success', __('user.resetpasswordlink', ['email' => $email]));
                case Password::INVALID_USER:
                    return redirect()->back()->withErrors(['email' => trans($response)]);
            }
        }
    }

    /**
     * Apply bulk action on selected user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkaction(Request $request)
    {
        $user = new User();
        if ($request->get('bulk-action') == 'delete') {
            User::destroy($request->get('id'));
            $message = __('user.delete_success');
        } elseif ($request->get('bulk-action') == 'active') {
            $needVerify = false;
            foreach ($request->get('id') as $userId) {
                $user = User::findOrFail($userId);
                if ($user->email_verified_at == null) {
                    $needVerify= true;
                } else {
                    User::where('id', $userId)->update(['status' => 1]);
                }
            }
            if ($needVerify == false) {
                $message = __('user.active_success');
            } else {
                $message = __('user.active_success_validate');
            }
        } elseif ($request->get('bulk-action') == 'inactive') {
            User::whereIn('id', $request->get('id'))->update(['status' => 0]);
            $message = __('user.inactive_success');
        }
        \Session::flash('success', $message);
        event(new BulkAction($user->getTable(), $request->get('id'), $request->get('bulk-action')));
        return redirect()->back();
    }

    /**
     * Apply change status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changestatus(Request $request)
    {
        $user = new User();
        $user = User::findOrFail($request->id);
        if ($user->status  == 1) {
            User::where('id', $request->id)->update(['status' => 0]);
            \Session::flash('success', __('user.account_inactivated_success'));
        } else {
            if ($user->email_verified_at == null) {
                \Session::flash('error', __('user.account_not_validated'));
            } else {
                User::where('id', $request->id)->update(['status' => 1]);
                \Session::flash('success', __('user.account_activated_success'));
            }
        }
        return redirect()->back();
    }
}
