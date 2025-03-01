<?php

namespace App\Domains\Client\Listeners;

use App\Domains\Webhook\Events\WebhookReceived;
use App\Domains\Client\Services\ClientService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateClientListener implements ShouldQueue
{

    use InteractsWithQueue;

    protected ClientService $clientService;

    public function __construct(ClientService $clientService) {

        $this->clientService = $clientService;
    }

    public function handle(WebhookReceived $event) {
        $this->clientService->createClient($event->data);
    }
}
