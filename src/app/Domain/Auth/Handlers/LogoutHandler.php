<?php declare(strict_types=1);

namespace App\Domain\Auth\Handlers;

use App\Shared\Handler;
use App\Domain\Auth\Commands\LogoutCommand;
use Illuminate\Support\Facades\Log;

final class LogoutHandler extends Handler
{
    /**
     * Deletes the current user's access token to log out.
     *
     * @param LogoutQuery $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(LogoutCommand $command): bool
    {
        try {
            return $command->request
                ->user()
                ->currentAccessToken()
                ->delete();
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Logout handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Logout failed. Please try again',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
