<?php

namespace App\Domains\Client\Listeners;

use App\Domains\Webhook\Events\WebhookReceived;
use App\Domains\Client\Services\ClientService;

use Illuminate\Queue\InteractsWithQueue;

class CreateClientListener
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
