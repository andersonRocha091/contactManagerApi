<?php

namespace App\Domains\Client\Listeners;

use App\Domains\Shared\Events\ClientCreatedSuccesfully;
use App\Domains\Client\Infrastructure\Jobs\SendClientEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;

class SendClientEmailListener {

    public function handle(ClientCreatedSuccesfully $event) {

        SendClientEmailJob::dispatch($event->client)
        ->delay(now()->addSeconds(5));
    }

}

