<?php declare(strict_types=1);

namespace App\Domain\Task\Handlers;

use App\Shared\Handler;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\Queries\GetTaskByIdQuery;
use App\Models\Task as TaskEloquent;

final class GetTaskByIdHandler extends Handler
{
    /**
     * Constructs a new GetTaskByIdHandler instance.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    /**
     * Handles fetching a task by its ID.
     *
     * @param GetTaskByIdQuery $query
     * @return TaskEloquent
     * 
     * @throws \RuntimeException
     */
    public function handle(GetTaskByIdQuery $query): TaskEloquent
    {
        $task = $this->repository->findById(
            id: $query->taskId
        );

        if ($task === null) {
            throw new \RuntimeException(
                message: "Task with ID {$command->id} not found."
            );
        }

        return $task;
    }
}
