<?php

namespace App\Services\ApiAuth;

interface UserRepositoryInterface
{
    public function getUserByToken(string $token);
}
