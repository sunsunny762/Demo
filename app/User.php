<?php

namespace App;

use Event;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DbEvents;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, DbEvents;

    /**
     * Overwrite created_by field value with currently logged in user.
     * Set @var has_created_by to false if created_by field does not exist in DB Table.
     *
     * @var boolean
     */
    protected $has_created_by = true;

    /**
     * Overwrite updated_by field value with currently logged in user.
     * Set @var has_updated_by to false if created_by field does not exist in DB Table.
     *
     * @var boolean
     */

    protected $has_updated_by = true;

    /**
     * Define feilds name which have html tags
     * Set @var notStripTags add DB Table column name which column have html tags.
     *
     * @var array
    */

    public static $notStripTags = ['email'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_id',
        'email',
        'password',
        'first_name',
        'last_name',
        'profile_image',
        'login_attempt_count',
        'email_verified_at',
        'block',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User & User Type Relationship [Many to One]
     * E.g.: Multiple Users are Available with Admin User Role.
     */
    public function userType()
    {
        return $this->belongsTo('App\UserType');
    }

        /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // Activate account on Password Reset.
        Event::listen('Illuminate\Auth\Events\PasswordReset', function ($model) {
            if (is_null($model->user->email_verified_at)) {
                $user = User::find($model->user->id);
                $user->fill(['email_verified_at' => \Carbon\Carbon::now()]);
                $user->save();
            }
        });
    }

    //get last login value by build relation ship with activity log tabe
    public function lastLogin()
    {
        return $this->hasOne('App\ActivityLog')->where('activity', '=', 'login')->orderBy('id', 'desc');
    }

    //get full name
    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user list based on search criteria.
     * @param  \Illuminate\Http\Request  $request
     * @return object App\User
     */
    public function getResult($request)
    {
        // Set default parameter values
        // Curretly it is not in Admin user listing as we are using data table sorting
        // but have kept this for API integration
        $order_by = !empty($request->get('order_by')) ? ($request->get('order_by') == 'name') ? 'first_name' : $request->get('order_by') : 'first_name';
        $order = !empty($request->get('order')) ? $request->get('order') : 'asc';

        // Fetch users list
        $users = new User;

        // Search
        if (!empty($request->get('search'))) {
            $searchStr = $request->get('search');
            //$searchStr = addCslashes($searchStr, "\\"); 
           
             $escape = "ESCAPE '|'";
             if(substr_count($searchStr,"|")){
                $searchStr = str_replace('\\', '\\\\\\', $searchStr);
                 $escape = "";
             }
            //$searchStr =  str_replace("|",'\\',$searchStr);
            // added escape for searching backslash issue DLC-140
            $users = $users->where(function ($query) use ($searchStr,$escape) {
                $query
                ->whereRaw('first_name LIKE ? '.$escape, '%'.$searchStr.'%')
                ->orWhereRaw('last_name LIKE ?  '.$escape, '%'.$searchStr.'%')
                ->orWhereRaw('email LIKE ?  '.$escape, '%'.$searchStr.'%')
                ->orWhereRaw('CONCAT(first_name, " ",last_name) LIKE ?  '.$escape, '%'.$searchStr.'%');
            });
        }

        // Status
        if ($request->get('status') !== null) {
            $users = $users->where('status', $request->get('status'));
        }
        //get last login value by relation ship
        $users = $users->with('lastLogin');

        //if per_page not set then all record value use for per_page
        // Curretly it is not in Admin user listing as we are using data table paging
        // but have kept this for API integration
        $per_page = !empty($request->get('per_page')) ? $request->get('per_page') : $users->count();
        if ($order_by == 'first_name') {
            $users = $users->orderBy($order_by, $order)->orderBy('last_name', $order);
        } else {
            $users = $users->orderBy($order_by, $order);
        }
        //dd($users->toSql());
        //dd($users->getBindings());
        $users = $users->paginate($per_page);
        return $users;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }
}
