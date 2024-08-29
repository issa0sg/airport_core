<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiAuthenticate
{
    public function __construct(protected ApiAuthServiceInterface $apiAuthService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->apiAuthService->authorizeUser($request);
            return $next($request);
        } catch (Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Validation error'], 401);
        }
    }
}
