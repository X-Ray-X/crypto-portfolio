<?php

namespace App\Repositories;

use App\Exceptions\RepositoryOperationException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
     * @param string $username
     * @return User
     *
     * @throws ModelNotFoundException
     */
    public function getUserByUsername(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
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

    /**
     * This method returns API Key value that can be passed to the user.
     *
     * @param User $user
     * @return string
     * @throws RepositoryOperationException
     */
    public function createUserApiKey(User $user): string
    {
        $apiKey = base64_encode(Str::random(40));

        if (!$user->auth()->updateOrCreate(['user_id' => $user->id], ['api_key' => Hash::make($apiKey)])) {
            throw new RepositoryOperationException('The system was unable to save the API Key hash in the database');
        }

        return $apiKey;
    }
}
