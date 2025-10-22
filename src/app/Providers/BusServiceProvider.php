<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class BusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: \App\Contracts\CommandBusContract::class,
            concrete: \App\Buses\CommandBus::class
        );

        $this->app->singleton(
            abstract: \App\Contracts\QueryBusContract::class,
            concrete: \App\Buses\QueryBus::class
        );
    }
}
