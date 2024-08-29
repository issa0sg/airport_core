<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\ApiAuth\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function getUserByToken(string $token)
    {
        return User::query()->latest()->first();
    }
}
