<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\RedirectAfterRegistration;
use Illuminate\Auth\Events\Registered;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::listen(
            Registered::class,
            RedirectAfterRegistration::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
