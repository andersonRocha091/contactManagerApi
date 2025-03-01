<?php
namespace App\Domains\Webhook\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookReceived {

    use Dispatchable, SerializesModels;

    public array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }
}