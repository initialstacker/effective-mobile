<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\QueryBusContract;

final class QueryServiceProvider extends ServiceProvider
{
    /**
     * List of [query, handler] pairs for auth domain.
     *
     * @var array<array{0: class-string, 1: class-string}>
     */
    private array $auth = [
        [
            \App\Domain\Auth\Queries\CheckMeQuery::class,
            \App\Domain\Auth\Handlers\CheckMeHandler::class
        ],
    ];

    /**
     * List of [query, handler] pairs for task domain.
     *
     * @var array<array{0: class-string, 1: class-string}>
     */
    private array $task = [
        [
            \App\Domain\Task\Queries\GetAllTasksQuery::class,
            \App\Domain\Task\Handlers\GetAllTasksHandler::class
        ],
        [
            \App\Domain\Task\Queries\GetTaskByIdQuery::class,
            \App\Domain\Task\Handlers\GetTaskByIdHandler::class
        ],
    ];

    /**
     * Returns an iterable of [queryClass, handlerClass] pairs for all domains.
     *
     * @return iterable<array{0: class-string, 1: class-string}>
     */
    private function queryHandlerPairs(): iterable
    {
        yield from $this->auth;
        yield from $this->task;
    }

    /**
     * Returns a merged map of query class => handler class.
     *
     * @return array<class-string, class-string>
     */
    private function queryHandlerMap(): array
    {
        $map = [];

        foreach ($this->queryHandlerPairs() as [$query, $handler]) {
            $map[$query] = $handler;
        }

        return $map;
    }

    /**
     * Register all query handlers with the query bus.
     *
     * @param QueryBusContract $queryBus
     * @return void
     */
    public function boot(QueryBusContract $queryBus): void
    {
        $queryBus->register(map: $this->queryHandlerMap());
    }
}
