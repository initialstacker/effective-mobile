<?php declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Models\Task as TaskEloquent;

final class TaskEloquentRepository extends TaskRepository
{
    /**
     * The Eloquent model instance for tasks.
     *
     * @param TaskEloquent $task
     */
    public function __construct(
        private readonly TaskEloquent $task
    ) {}

    /**
     * Retrieve all tasks.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->task->query()->get()->all();
    }

    /**
     * Find a task by its ID.
     *
     * @param int $id
     * @return TaskEloquent|null
     */
    public function findById(int $id): ?TaskEloquent
    {
        return $this->task->query()->find(id: $id);
    }

    /**
     * Save a task model to the data source.
     *
     * @param TaskEloquent $task
     * @return void
     */
    public function save(TaskEloquent $task): void
    {
        $task->save();
    }

    /**
     * Delete a task model from the data source.
     *
     * @param TaskEloquent $task
     * @return void
     */
    public function delete(TaskEloquent $task): void
    {
        $task->delete();
    }
}
