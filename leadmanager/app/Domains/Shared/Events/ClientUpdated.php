<?php

namespace App\Domains\Shared\Events;

use App\Domains\Client\Domain\Entities\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientUpdate {

    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $client;
    public function __construct(Client $client) {
        $this->client = $client;
    }
}