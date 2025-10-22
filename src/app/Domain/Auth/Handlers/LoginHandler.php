<?php declare(strict_types=1);

namespace App\Domain\Auth\Handlers;

use App\Shared\Handler;
use App\Domain\Auth\Commands\LoginCommand;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\{Hash, Log};

final class LoginHandler extends Handler
{
    /**
     * Constructs a new LoginHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handle the login command.
     *
     * @param LoginCommand $command
     * @return string|null
     *
     * @throws ValidationException
     * @throws \RuntimeException
     */
    public function handle(LoginCommand $command): ?string
    {
        try {
            $user = $this->repository->findByEmail(
                email: $command->email
            );

            $pwdCheck = $user && Hash::check(
                value: $command->password,
                hashedValue: $user->password
            );

            if (!$user || !$pwdCheck) {
                $message = 'Invalid email or password.';
                
                throw ValidationException::withMessages(
                    messages: ['email' => [$message]]
                );
            }

            $token = $user->createToken(name: 'auth_token');

            return $token->plainTextToken;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Login handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Login failed. Please try again',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
