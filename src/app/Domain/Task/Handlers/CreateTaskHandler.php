<?php declare(strict_types=1);

namespace App\Domain\Task\Handlers;

use App\Shared\Handler;
use App\Domain\Task\Commands\CreateTaskCommand;
use App\Models\Task as TaskEloquent;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Log;

final class CreateTaskHandler extends Handler
{
    /**
     * Constructs a new CreateTaskHandler instance.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    /**
     * Handles the creation of a new task.
     *
     * @param CreateTaskCommand
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(CreateTaskCommand $command): bool
    {
        try {
            $task = TaskEloquent::create(attributes: [
                'title' => $command->title,
                'description' => $command->description,
                'status' => $command->status
            ]);

            $this->repository->save(task: $task);

            return $task !== null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Create task handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to create task due to error.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
