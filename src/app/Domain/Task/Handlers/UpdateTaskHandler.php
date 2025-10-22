<?php declare(strict_types=1);

namespace App\Domain\Task\Handlers;

use App\Shared\Handler;
use App\Domain\Task\Commands\UpdateTaskCommand;
use App\Models\Task as TaskEloquent;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Log;

final class UpdateTaskHandler extends Handler
{
    /**
     * Constructs a new UpdateTaskHandler instance.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    /**
     * Handles updating an existing task.
     *
     * @param UpdateTaskCommand $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(UpdateTaskCommand $command): bool
    {
        try {
            $task = $this->repository->findById(id: $command->id);

            if ($task === null) {
                throw new \RuntimeException(
                    message: "Task with ID {$command->id} not found."
                );
            }

            $updated = $task->update([
                'title' => $command->title,
                'description' => $command->description,
                'status' => $command->status
            ]);

            if (!$updated) {
                throw new \RuntimeException(
                    message: "Failed to update task with ID {$command->id}."
                );
            }

            $this->repository->save(task: $task);

            return $task !== null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Update task handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to update task due to error.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
