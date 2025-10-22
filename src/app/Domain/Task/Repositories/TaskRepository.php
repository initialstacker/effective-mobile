<?php declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Models\Task as TaskEloquent;

abstract class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Retrieve all tasks.
     *
     * @return array
     */
    abstract public function all(): array;

    /**
     * Find a task by its unique identifier.
     *
     * @param int $id
     * @return TaskEloquent|null
     */
    abstract public function findById(int $id): ?TaskEloquent;
}
