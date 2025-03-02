<?php

namespace App\Domains\Client\Listeners;

use App\Domains\Webhook\Events\WebhookReceived;
use App\Domains\Client\Services\ClientService;
use App\Domains\Shared\Exceptions\ClientCreationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use App\Domains\Shared\Events\ClientCreatedSuccesfully;
class CreateClientListener
{

    use InteractsWithQueue;

    protected ClientService $clientService;

    public function __construct(ClientService $clientService) {

        $this->clientService = $clientService;
    }

    public function handle(WebhookReceived $event) {
        
        try {

            $this->validateData($event->data);

            $client = $this->clientService->createClient($event->data);
            
            event(new ClientCreatedSuccesfully($client));

        } catch (\InvalidArgumentException $e) {

            Log::error("Invalid client data: {$e->getMessage()}", [
                'payload' => $event->data,
                'error' => $e
            ]);
            throw new ClientCreationException(
                "Validation failed: {$e->getMessage()}", 
                400, 
                $e
            );

        } catch (\Throwable $e) {
             Log::critical("Client creation failed: {$e->getMessage()}", [
                'payload' => $event->data,
                'trace' => $e->getTraceAsString()
            ]);
        }

    }

    protected function validateData(array $data):void {
    
        if (empty($data['email']) || (empty($data['name']) || empty($data['age']))) {
            throw new \InvalidArgumentException('There are missing required fields');
        }       
    }
}
