<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class BulkAction
{
    use SerializesModels;
    
    public $model, $ids, $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model, $ids, $action)
    {
        $this->model = $model;
        $this->ids = $ids;
        $this->action = $action;
    }

}
