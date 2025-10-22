<?php declare(strict_types=1);

namespace App\Domain\Task\Handlers;

use App\Shared\Handler;
use App\Domain\Task\Commands\DeleteTaskCommand;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Log;

final class DeleteTaskHandler extends Handler
{
    /**
     * Constructs a new DeleteTaskHandler instance.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    /**
     * Handles the deletion of a task by its ID.
     *
     * @param DeleteTaskCommand $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(DeleteTaskCommand $command): bool
    {
        try {
            $task = $this->repository->findById(id: $command->id);

            if ($task === null) {
                throw new \RuntimeException(
                    message: "Task with ID {$command->id} not found."
                );
            }

            $this->repository->delete(task: $task);

            return true;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Delete task handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to delete task due to error.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
