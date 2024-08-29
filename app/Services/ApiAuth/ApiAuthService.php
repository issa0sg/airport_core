<?php

namespace App\Services\ApiAuth;

use App\Http\Middleware\Api\ApiAuthServiceInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthService implements ApiAuthServiceInterface
{

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function authorizeUser(Request $request): Authenticatable
    {
        $token = $request->header('Token', 'sampletoken');

        $user = $this->userRepository->getUserByToken($token);

        Auth::login($user);

        return $user;
    }
}
