<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array[]
     */
    public function transform(User $user): array
    {
        return [
            $user->id => [
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'firstName' => $user->first_name ?? null,
                'lastName' => $user->last_name ?? null,
            ]
        ];
    }
}
