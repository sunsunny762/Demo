<?php

namespace App\Listeners\ActivityLog;

use Auth;

class BulkActionListener extends ActivityLogListener
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
        // echo "test";
        $data = [
            'session_id' => session()->getId(),
            'user_id' => !empty(Auth::id()) ? Auth::id() : 0,
            'model' => $event->model,
            'activity' => $event->action,
            'data' => $event->ids,
            'ip_address' => \Request::getClientIp(true)
        ];

        $this->save($data);
    }
}
