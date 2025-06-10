<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();
        $this->loadMigrationsFrom([
            database_path('migrations'),
            database_path('migrations/ticketing'),
            database_path('migrations/unit_kerja'),
            database_path('migrations/ssd'),
        ]);
    }
}
