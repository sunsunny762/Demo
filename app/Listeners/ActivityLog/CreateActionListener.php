<?php

namespace App\Listeners\ActivityLog;

use Auth;

class CreateActionListener extends ActivityLogListener
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
            'model' => $event->model->getTable(),
            'pk_id' => $event->model->{$event->model->getKeyName()},
            'activity' => 'create',
            'data' => $event->model,
            'ip_address' => \Request::getClientIp(true)
        ];

        $this->save($data);
    }
}
