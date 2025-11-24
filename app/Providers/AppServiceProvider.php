<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'production')) {
            $this->app['config']->set('view.cache', false);
            $this->app['config']->set('view.compiled', storage_path('framework/views'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // PAKSA SEMUA url() dan route() jadi HTTPS di production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
