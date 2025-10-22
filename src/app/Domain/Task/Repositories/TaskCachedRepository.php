<?php declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Models\Task as TaskEloquent;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

final class TaskCachedRepository extends TaskRepository
{
    /**
     * Cache time-to-live in minutes.
     *
     * @var int
     */
    private int $ttl = 60;

    /**
     * Cache key for storing all tasks.
     */
    private const string CACHE_KEY_ALL = 'tasks.all';

    /**
     * Prefix for cache keys for individual tasks.
     */
    private const string CACHE_KEY_PREFIX = 'task.id.';

    /**
     * Constructs a new TaskCachedRepository instance.
     *
     * @param TaskEloquentRepository $eloquent
     * @param TaskTransactionRepository $transaction
     */
    public function __construct(
        private TaskEloquentRepository $eloquent,
        private TaskTransactionRepository $transaction
    ) {}

    /**
     * Retrieve all tasks, using cache to optimize performance.
     *
     * @return TaskEloquent[]
     */
    public function all(): array
    {
        return Cache::remember(
            key: self::CACHE_KEY_ALL,
            ttl: Carbon::now()->addMinutes(value: $this->ttl),
            callback: fn () => $this->eloquent->all()
        );
    }

    /**
     * Retrieve a task by its ID, using cache for faster access.
     *
     * @param int $id
     * @return TaskEloquent|null
     */
    public function findById(int $id): ?TaskEloquent
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        return Cache::remember(
            key: $cacheKey,
            ttl: Carbon::now()->addMinutes(value: $this->ttl),
            callback: function () use ($id) {
                return $this->eloquent->findById(id: $id);
            }
        );
    }

    /**
     * Save a task and clear relevant cache entries.
     *
     * @param TaskEloquent $task
     */
    public function save(TaskEloquent $task): void
    {
        $this->transaction->save(task: $task);

        Cache::forget(key: self::CACHE_KEY_ALL);
        Cache::forget(key: self::CACHE_KEY_PREFIX . $task->id);
    }

    /**
     * Delete a task and clear relevant cache entries.
     *
     * @param TaskEloquent $task
     */
    public function delete(TaskEloquent $task): void
    {
        $this->transaction->delete(task: $task);

        Cache::forget(key: self::CACHE_KEY_ALL);
        Cache::forget(key: self::CACHE_KEY_PREFIX . $task->id);
    }
}
