<?php declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Models\User as UserEloquent;

interface UserRepositoryInterface
{
    /**
     * Finds a User by their email address.
     *
     * @param string $email
     * @return UserEloquent|null
     */
    public function findByEmail(string $email): ?UserEloquent;
}
