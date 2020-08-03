<?php

namespace App\Listeners\ActivityLog\Auth;

use Auth;
use App\Listeners\ActivityLog\ActivityLogListener;

class FailedActionListener extends ActivityLogListener
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
        if(!empty($event->user)) {
            // echo "test";
            $data = [
                'session_id' => session()->getId(),
                'user_id' => $event->user->{$event->user->getKeyName()},
                'pk_id' => $event->user->{$event->user->getKeyName()},
                'model' => $event->user->getTable(),
                'activity' => 'login-failed',
                'data' => [],
                'ip_address' => \Request::getClientIp(true)
            ];

            $this->save($data);
        }
    }
}
