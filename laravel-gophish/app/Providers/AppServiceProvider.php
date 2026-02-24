<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS if configured in APP_URL
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        } elseif ($this->app->environment('production')) {
             // Fallback for production if someone forgot APP_URL but wants security
             URL::forceScheme('https');
        }
    }
}
