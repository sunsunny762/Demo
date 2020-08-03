<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class UpdateAction
{
    use SerializesModels;
    
    public $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

}
