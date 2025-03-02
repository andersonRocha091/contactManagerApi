<?php

namespace App\Domains\Http\Infrastructure\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domains\Client\Domain\Entities\Client;


class ClientCreatedMail extends Mailable {

    use Queueable, SerializesModels;

    public Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function build(): self {
        return $this->subject('Welcome to the platform')
        ->view('emails.client_welcome')
        ->with(['client' => $this->client]);
    }
}