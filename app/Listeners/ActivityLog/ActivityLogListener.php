<?php

namespace App\Listeners\ActivityLog;

use App\ActivityLog;

class ActivityLogListener
{
    /**
     * Save data to activity log.
     *
     * @param  array  $data
     * @return void
     */
    public function save($data)
    {
        $activitylog = new ActivityLog;
        $activitylog->fill($data);
        $activitylog->save();
    }
}
