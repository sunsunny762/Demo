<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class UserType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * Define feilds name which have html tags
     * Set @var notStripTags add DB Table column name which column have html tags.
     *
     * @var array
     */

    public static $notStripTags = [];

    /**
     * User & User Type Relationship [Many to One]
     * E.g.: Multiple Users are Available with Admin User Role.
     */
    public function user()
    {
        return $this->hasMany('App\User');
    }
}
