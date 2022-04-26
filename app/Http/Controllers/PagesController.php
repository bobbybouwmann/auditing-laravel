<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\DashboardVisited;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PagesController extends Controller
{
    public function dashboard(Request $request)
    {
        Gate::authorize('dashboard');

        /** @var User $user */
        $user = $request->user();
        $user->load('contracts');

        $user->notify(new DashboardVisited());

        return view('dashboard');
    }
}
