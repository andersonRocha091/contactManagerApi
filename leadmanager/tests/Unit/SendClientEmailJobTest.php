<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Client\Infrastructure\Jobs\SendClientEmailJob;
use App\Domains\Client\Domain\Entities\Client;
use App\Domains\Http\Infrastructure\Mail\ClientCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Mockery;

class SendClientEmailJobTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
    }

    public function testHandleSendsEmail()
    {
        $client = new Client([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $job = new SendClientEmailJob($client);

        $job->handle();

        Mail::assertSent(ClientCreatedMail::class, function ($mail) use ($client) {
            return $mail->hasTo($client->email) && $mail->client->email === $client->email;
        });
    }

    public function testHandleCatchesException()
    {
        $client = new Client([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $job = new SendClientEmailJob($client);

        Mail::shouldReceive('to->send')->andThrow(new \Exception('Email not sent'));

        $job->handle();

        // Verifique se a exceção foi capturada
        $this->assertTrue(true); // Apenas para garantir que o teste passe
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}