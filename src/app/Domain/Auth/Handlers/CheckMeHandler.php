<?php declare(strict_types=1);

namespace App\Domain\Auth\Handlers;

use App\Shared\Handler;
use App\Domain\Auth\Queries\CheckMeQuery;
use App\Models\User as UserEloquent;
use Illuminate\Support\Facades\Log;

final class CheckMeHandler extends Handler
{
    /**
     * Handler to retrieve the currently authenticated user.
     *
     * @param CheckMeQuery $query
     * @return UserEloquent|null
     *
     * @throws \RuntimeException
     */
    public function handle(CheckMeQuery $query): ?UserEloquent
    {
        try {
            $user = $query->request->user();

            return $user ?? null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                CheckMe handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to retrieve authenticated user.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
