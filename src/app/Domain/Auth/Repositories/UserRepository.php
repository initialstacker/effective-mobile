<?php declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Models\User as UserEloquent;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * User model instance for database operations.
     * 
     * @param UserEloquent $user
     */
    public function __construct(
        private readonly UserEloquent $user
    ) {}

    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return UserEloquent|null
     */
    public function findByEmail(string $email): ?UserEloquent
    {
        return $this->user->newQuery()->where(
            column: 'email',
            operator: '=',
            value: $email
        )->withOnly(
            relations: 'role'
        )->first();
    }
}
