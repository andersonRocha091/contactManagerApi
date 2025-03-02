<?php

namespace App\Providers;

use App\Domains\Client\Listeners\CreateClientListener;
use App\Domains\Webhook\Events\WebhookReceived;
use App\Domains\Shared\Events\ClientCreatedSuccesfully;
use App\Domains\Client\Listeners\SendClientEmailListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{   
    protected $listen = [
        WebhookReceived::class => [
            CreateClientListener::class,
        ],
        ClientCreatedSuccesfully::class => [
            SendClientEmailListener::class
        ]
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
