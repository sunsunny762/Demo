<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{

    /**
     * The name of the "updated_at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * Define feilds name which have html tags
     * Set @var notStripTags add DB Table column name which column have html tags.
     *
     * @var array
     */

    public static $notStripTags = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'user_id',
        'model',
        'activity',
        'pk_id',
        'data',
        'ip_address',
        'created_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object'
    ];
}
