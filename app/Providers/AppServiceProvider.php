<?php

namespace App\Providers;

use App\Http\Middleware\Api\ApiAuthServiceInterface;
use App\Repositories\AirportRepository;
use App\Repositories\UserRepository;
use App\Services\ApiAuth\ApiAuthService;
use App\Services\ApiAuth\UserRepositoryInterface;
use App\Services\DataPersistence\Implements\HydratableRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Airport\AirportRepository as AirportRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ApiAuthServiceInterface::class, ApiAuthService::class);
        $this->app->bind(HydratableRepository::class, AirportRepository::class);
        $this->app->bind(AirportRepositoryInterface::class, AirportRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
