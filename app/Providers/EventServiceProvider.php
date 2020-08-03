<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\CreateAction;
use App\Events\UpdateAction;
use App\Events\BulkAction;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\PasswordReset;

use App\Listeners\ActivityLog\CreateActionListener;
use App\Listeners\ActivityLog\UpdateActionListener;
use App\Listeners\ActivityLog\BulkActionListener;
use App\Listeners\ActivityLog\Auth\LoginActionListener;
use App\Listeners\ActivityLog\Auth\LogoutActionListener;
use App\Listeners\ActivityLog\Auth\FailedActionListener;
use App\Listeners\ActivityLog\Auth\PasswordResetActionListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BulkAction::class => [
            BulkActionListener::class
        ],
        CreateAction::class => [
            CreateActionListener::class
        ],
        UpdateAction::class => [
            UpdateActionListener::class
        ],
        Login::class => [
            LoginActionListener::class
        ],
        Logout::class => [
            LogoutActionListener::class
        ],
        Failed::class => [
            FailedActionListener::class
        ],
        PasswordReset::class => [
            PasswordResetActionListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
