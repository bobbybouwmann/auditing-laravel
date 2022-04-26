<?php

namespace App\Models;

use App\Services\Auditor;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::retrieved(function (Model $model) {
            $auditor = app(Auditor::class);
            $auditor->addModel($model);
        });
    }
}
