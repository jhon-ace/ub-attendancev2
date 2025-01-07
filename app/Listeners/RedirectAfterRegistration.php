<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class RedirectAfterRegistration implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return Redirect::route('admin.dashboard');
        }
        else if (Auth::check() && Auth::user()->hasRole('employee')){
            return Redirect::route('employee.dashboard');
        }
    }
}
