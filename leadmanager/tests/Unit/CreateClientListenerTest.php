<?php

namespace Tests\Unit;

use App\Domains\Client\Listeners\CreateClientListener;
use App\Domains\Client\Services\ClientService;
use App\Domains\Webhook\Events\WebhookReceived;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateClientListenerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testListenerProcessesWebhookData(): void
    {
        $clientServiceMock = Mockery::mock(ClientService::class);
        $clientServiceMock->shouldReceive('handle')->once();

        $listener = new CreateClientListener($clientServiceMock);
        $event = new WebhookReceived([
            "name"=>"ivan",
            "email"=>"ivan@gmail.com",
            "phone"=> "75999125623",
            "address"=>"asdfasdfasdf",
            "city"=>"Feira de Santana",
            "state"=>"Bahia",
            "zip"=>"45011256",
            "picture"=>"pic",
            "age"=>12
        ]);

        $listener->handle($event);
    }
}
