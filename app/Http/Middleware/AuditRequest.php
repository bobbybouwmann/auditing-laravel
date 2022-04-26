<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auditor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuditRequest
{
    public function __construct(private Auditor $auditor)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (Route::current()) {
            $this->auditor->onRoute(
                Route::currentRouteName() ?? Route::currentRouteAction()
            );
        }

        if ($request->user() instanceof User) {
            $this->auditor->addUser($request->user());
        }

        $response = $next($request);

        $this->auditor->onUrl($request->fullUrl());

        $this->auditor->finish();

        return $response;
    }
}
