<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CommandBusContract;

final class CommandServiceProvider extends ServiceProvider
{
    /**
     * List of [command, handler] pairs for authentication domain.
     *
     * @var array<array{0: class-string, 1: class-string}>
     */
    private array $auth = [
        [
            \App\Domain\Auth\Commands\LoginCommand::class,
            \App\Domain\Auth\Handlers\LoginHandler::class
        ],
        [
            \App\Domain\Auth\Commands\LogoutCommand::class,
            \App\Domain\Auth\Handlers\LogoutHandler::class
        ],
    ];

    /**
     * List of [command, handler] pairs for task domain.
     *
     * @var array<array{0: class-string, 1: class-string}>
     */
    private array $task = [
        [
            \App\Domain\Task\Commands\CreateTaskCommand::class,
            \App\Domain\Task\Handlers\CreateTaskHandler::class
        ],
        [
            \App\Domain\Task\Commands\UpdateTaskCommand::class,
            \App\Domain\Task\Handlers\UpdateTaskHandler::class
        ],
        [
            \App\Domain\Task\Commands\DeleteTaskCommand::class,
            \App\Domain\Task\Handlers\DeleteTaskHandler::class
        ],
    ];

    /**
     * Returns an iterable of [commandClass, handlerClass] pairs for all domains.
     *
     * @return iterable<array{0: class-string, 1: class-string}>
     */
    private function commandHandlerPairs(): iterable
    {
        yield from $this->auth;
        yield from $this->task;
    }

    /**
     * Returns a map of command class => handler class.
     *
     * @return array<class-string, class-string>
     */
    private function commandHandlerMap(): array
    {
        $map = [];

        foreach ($this->commandHandlerPairs() as [$command, $handler]) {
            $map[$command] = $handler;
        }

        return $map;
    }

    /**
     * Registers all command-handler mappings with the application's command bus.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->make(
            abstract: CommandBusContract::class
        )->register(
            map: $this->commandHandlerMap()
        );
    }
}
