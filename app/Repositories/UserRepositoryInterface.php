<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User;

    /**
     * @param string $userId
     * @return User
     */
    public function getUserById(string $userId): User;

    /**
     * @param string $userId
     * @param array $userData
     * @return bool
     */
    public function update(string $userId, array $userData): bool;

    /**
     * @param string $userId
     * @return bool
     */
    public function delete(string $userId): bool;
}
