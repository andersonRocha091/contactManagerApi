<?php

namespace App\Domains\Client\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Domains\Http\Infrastructure\Mail\ClientCreatedMail;
use App\Domains\Client\Domain\Entities\Client;


class SendClientEmailJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function handle(): void {
        try {
            Mail::to($this->client->email)
            ->send(new ClientCreatedMail($this->client));
        } catch (\Throwable $th) {
            $test = $th;
        }
    }
}