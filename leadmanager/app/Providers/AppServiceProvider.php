<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Client\Repositories\ClientRepositoryInterface;
use App\Domains\Client\Repositories\EloquentClientRepository;
use App\Domains\Client\Services\ClientService;
use App\Domains\Client\Domain\Entities\Client;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientRepositoryInterface::class, function ($app) {
            return new EloquentClientRepository($app->make(Client::class));
        });
        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService($app->make(ClientRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
