<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: \App\Domain\Auth\Repositories\UserRepositoryInterface::class,
            concrete: \App\Domain\Auth\Repositories\UserRepository::class
        );
        
        $this->app->bind(
            abstract: \App\Domain\Task\Repositories\TaskRepositoryInterface::class,
            concrete: \App\Domain\Task\Repositories\TaskCachedRepository::class
        );
    }
}
