<?php

namespace App\Listeners\ActivityLog\Auth;

use Auth;
use App\Listeners\ActivityLog\ActivityLogListener;

class LoginActionListener extends ActivityLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        $data = [
            'session_id' => session()->getId(),
            'user_id' => !empty(Auth::id()) ? Auth::id() : 0,
            'pk_id' => $event->user->{$event->user->getKeyName()},
            'model' => $event->user->getTable(),
            'activity' => 'login',
            'data' => [],
            'ip_address' => \Request::getClientIp(true)
        ];

        $this->save($data);

        $user = $event->user;
        $user->last_login_ip = \Request::getClientIp(true);
        $user->save();
    }
}
