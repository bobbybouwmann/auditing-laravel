<?php

namespace App\Providers;

use App\Listeners\AuthorizeAbility;
use App\Listeners\AuthorizeMail;
use App\Listeners\AuthorizeNotification;
use Illuminate\Auth\Access\Events\GateEvaluated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        GateEvaluated::class => [
            AuthorizeAbility::class,
        ],
        MessageSent::class => [
            AuthorizeMail::class,
        ],
        NotificationSent::class => [
            AuthorizeNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
