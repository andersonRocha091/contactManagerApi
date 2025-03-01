<?php

namespace App\Providers;

use App\Domains\Client\Listeners\CreateClientListener;
use App\Domains\Webhook\Events\WebhookReceived;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{   
    protected $listen = [
        WebhookReceived::class => [
            CreateClientListener::class,
        ]
    ];
    // /**
    //  * Register services.
    //  */
    // public function register(): void
    // {
    //     //
    // }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
