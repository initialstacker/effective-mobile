<?php declare(strict_types=1);

namespace App\Contracts;

use App\Shared\Query;

interface QueryBusContract
{
    /**
     * Executes a query and returns the result.
     *
     * @param Query $query
     * @return mixed
     */
    public function ask(Query $query): mixed;

    /**
     * Registers a mapping of queries to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void;
}
