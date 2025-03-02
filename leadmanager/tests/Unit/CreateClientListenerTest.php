<?php

namespace Tests\Unit;

use App\Domains\Client\Listeners\CreateClientListener;
use App\Domains\Client\Services\ClientService;
use App\Domains\Webhook\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class CreateClientListenerTest extends TestCase {
   
    public function testListenerProcessesWebhookData(): void {
        $clientServiceMock = Mockery::mock(ClientService::class);
        $clientServiceMock->shouldReceive('createClient')->once();

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

    public function testListenerHandlesInvalidData(): void  {

        $clientServiceMock = Mockery::mock(ClientService::class);
        $clientServiceMock->shouldNotReceive('createClient');

        $listener = new CreateClientListener($clientServiceMock);
        $event = new WebhookReceived([
            "name" => "",
            "email" => "",
            "phone" => "75999125623",
            "address" => "asdfasdfasdf",
            "city" => "Feira de Santana",
            "state" => "Bahia",
            "zip" => "45011256",
            "picture" => "pic",
            "age" => 12
        ]);

        $this->expectException(\App\Domains\Shared\Exceptions\ClientCreationException::class);
        $listener->handle($event);
    }

    public function testListenerHandlesExceptionDuringClientCreation(): void  {
        $clientServiceMock = Mockery::mock(ClientService::class);
        $clientServiceMock->shouldReceive('createClient')->andThrow(new \Exception('Service error'));

        Log::shouldReceive('critical')
        ->once()
        ->with(
            'Client creation failed: Service error',
            Mockery::on(function ($context) {
                return is_array($context) && isset($context['payload']) && isset($context['trace']);
            })
        );

        $listener = new CreateClientListener($clientServiceMock);
        $event = new WebhookReceived(['email' => 'test@example.com', 'name' => 'test','age' => 17]);

        $listener->handle($event);
    }
}