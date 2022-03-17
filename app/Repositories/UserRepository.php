<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User
    {
        return User::create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => Crypt::encryptString($userData['password']),
            'phoneNumber' => $userData['phoneNumber'] ?? null,
            'firstName' => $userData['firstName'] ?? null,
            'lastName' => $userData['lastName'] ?? null,
        ]);
    }

    /**
     * @param string $userId
     * @return User
     *
     * @throws ModelNotFoundException
     */
    public function getUserById(string $userId): User
    {
        return User::findOrFail($userId);
    }

    /**
     * @param string $userId
     * @param array $userData
     * @return bool
     *
     * @throws ModelNotFoundException
     */
    public function update(string $userId, array $userData): bool
    {
        if (empty($userData)) {
            return true;
        }

        return User::findOrFail($userId)->update($userData);
    }

    /**
     * @param string $userId
     * @return bool
     *
     * @throws ModelNotFoundException
     */
    public function delete(string $userId): bool
    {
        return User::findOrFail($userId)->delete();
    }
}
