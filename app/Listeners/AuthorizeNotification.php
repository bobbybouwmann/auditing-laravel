<?php

namespace App\Listeners;

use App\Services\Auditor;
use Illuminate\Notifications\Events\NotificationSent;

class AuthorizeNotification
{
    public function __construct(private Auditor $auditor)
    {
    }

    public function handle(NotificationSent $event): void
    {
        $this->auditor->addNotification($event->notification, $event->channel);
    }
}
