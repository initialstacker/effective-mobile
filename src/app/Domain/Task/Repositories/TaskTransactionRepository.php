<?php declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Models\Task as TaskEloquent;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Database\QueryException;

final class TaskTransactionRepository implements TaskRepositoryInterface
{
    /**
     * The underlying eloquent repository for task operations.
     *
     * @param TaskEloquentRepository $eloquent
     */
    public function __construct(
        private TaskEloquentRepository $eloquent
    ) {}

    /**
     * Save a task inside a database transaction with retries.
     *
     * @param TaskEloquent $task
     * @throws \RuntimeException
     */
    public function save(TaskEloquent $task): void
    {
        try {
            DB::transaction(
                callback: fn () => $this->eloquent->save(task: $task),
                attempts: 3
            );
        }

        catch (QueryException $e) {
            Log::error(
                message: 'Database error: ' . $e->getMessage(),
                context: [
                    'code' => (int) $e->getCode(),
                    'bindings' => $e->getBindings(),
                    'sql' => $e->getSql()
                ]
            );

            throw new \RuntimeException(
                message: 'Error saving task: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Delete a task inside a database transaction with retries.
     *
     * @param TaskEloquent $task
     * @throws \RuntimeException
     */
    public function delete(TaskEloquent $task): void
    {
        try {
            DB::transaction(
                callback: fn () => $this->eloquent->delete(task: $task),
                attempts: 3
            );
        }

        catch (QueryException $e) {
            Log::error(
                message: 'Database error: ' . $e->getMessage(),
                context: [
                    'code' => (int) $e->getCode(),
                    'bindings' => $e->getBindings(),
                    'sql' => $e->getSql(),
                ]
            );

            throw new \RuntimeException(
                message: 'Error deleting task: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
