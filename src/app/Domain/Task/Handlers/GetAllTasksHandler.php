<?php declare(strict_types=1);

namespace App\Domain\Task\Handlers;

use App\Shared\Handler;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\Queries\GetAllTasksQuery;

final class GetAllTasksHandler extends Handler
{
    /**
     * Constructs a new GetAllTasksHandler instance.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    /**
     * Handles fetching all tasks.
     *
     * @param GetAllTasksQuery $query
     * @return array
     */
    public function handle(GetAllTasksQuery $query): array
    {
        return $this->repository->all();
    }
}
