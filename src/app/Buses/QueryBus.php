<?php declare(strict_types=1);

namespace App\Buses;

use App\Contracts\QueryBusContract;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Shared\Query;

final class QueryBus implements QueryBusContract
{
    /**
     * Constructs a new QueryBus instance.
     *
     * @param Dispatcher $queryBus
     */
    public function __construct(
        private Dispatcher $queryBus
    ) {}

    /**
     * Executes a query and returns the result.
     *
     * @param Query $query
     * @return mixed
     */
    public function ask(Query $query): mixed
    {
        return $this->queryBus->dispatch(
            command: $query
        );
    }

    /**
     * Registers a mapping of queries to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void
    {
        $this->queryBus->map(map: $map);
    }
}
