<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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

        // Rate Limiter untuk tamu anonim
        RateLimiter::for('tamu', function (Request $request) {
            return Limit::perMinutes(5, 3)
                ->response(function (Request $request, array $headers) {
                    return back()->with('error', 'Sabar ya, tunggu 5 menit lagi sebelum mengirim data!')->withHeaders($headers);
                });
        });
    }
}
