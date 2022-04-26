<?php

namespace App\Listeners;

use App\Services\Auditor;
use Illuminate\Mail\Events\MessageSent;

class AuthorizeMail
{
    public function __construct(private Auditor $auditor)
    {
    }

    public function handle(MessageSent $event): void
    {
        $this->auditor->addMail($event->message->toString());
    }
}
