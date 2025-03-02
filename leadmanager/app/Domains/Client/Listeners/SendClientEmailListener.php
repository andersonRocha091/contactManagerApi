<?php

namespace App\Domains\Client\Listeners;

use App\Domains\Shared\Events\ClientCreatedSuccesfully;
use App\Domains\Client\Infrastructure\Jobs\SendClientEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;

class SendClientEmailListener
{
    use InteractsWithQueue;

    public function handle(ClientCreatedSuccesfully $event)
    {
        try {
            $this->dispatchJob($event->client);
            Log::info('SendClientEmailJob dispatched successfully for client: ' . $event->client->email);
        } catch (\Exception $e) {
            Log::critical('Failed to dispatch SendClientEmailJob: ' . $e->getMessage(), [
                'client' => $event->client,
                'error' => $e
            ]);
        }
    }

    protected function dispatchJob($client) {
        return SendClientEmailJob::dispatch($client)
            ->delay(now()->addSeconds(5));
    }
}
