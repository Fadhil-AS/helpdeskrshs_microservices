<?php

namespace App\Services\Ticketing\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Ticketing\Console\Commands\AutoCloseOldTickets;

class TicketingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutoCloseOldTickets::class,
            ]);
        }
    }
}
