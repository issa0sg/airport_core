<?php

namespace App\Http\Middleware\Api;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

interface ApiAuthServiceInterface
{
    public function authorizeUser(Request $request): Authenticatable;
}
