<?php declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Models\Task as TaskEloquent;

interface TaskRepositoryInterface
{
    /**
     * Save the given task model into the data source.
     *
     * @param TaskEloquent $task
     * @return void
     */
    public function save(TaskEloquent $task): void;

    /**
     * Delete the given task model from the data source.
     *
     * @param TaskEloquent $task
     * @return void
     */
    public function delete(TaskEloquent $task): void;
}
