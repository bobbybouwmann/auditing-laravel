<?php

namespace App\Listeners;

use App\Services\Auditor;
use Illuminate\Auth\Access\Events\GateEvaluated;

class AuthorizeAbility
{
    public function __construct(private Auditor $auditor) {}

    public function handle(GateEvaluated $event): void
    {
        $this->auditor->addAbility($event->ability, $event->result);
    }
}
