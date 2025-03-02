<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Client\Repositories\ClientRepositoryInterface;
use App\Domains\Client\Repositories\EloquentClientRepository;
use App\Domains\Client\Services\ClientService;
use App\Domains\Client\Domain\Entities\Client;
use App\Domains\Auth\Services\UserService;
use App\Domains\Client\Listeners\CreateClientListener;

use App\Domains\Voip\Interfaces\VoipCallServiceInterface;
use App\Domains\Voip\Infrastructure\Adapters\TwillioCallAdapter;

class AppServiceProvider extends ServiceProvider {
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

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });

        $this->app->singleton(CreateClientListener::class, function ($app) {
            return new CreateClientListener($app->make(ClientService::class));
        });

        $this->app->bind(VoipCallServiceInterface::class, function () {
            $provider = config('services.voip.provider', 'twilio');

            switch ($provider) {
                case 'twilio':
                default:
                    return new TwillioCallAdapter();
            }
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
