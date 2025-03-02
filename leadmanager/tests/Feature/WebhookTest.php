<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Domains\Webhook\Events\WebhookReceived;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class WebhookTest extends TestCase {
    
    use WithoutMiddleware;
    
    public function testWebhookDispatchesEventTriggeringClientModule () {
        Event::fake();

        $response = $this->postJson('api/webhook', [
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

        $response->assertOk();
        Event::assertDispatched(WebhookReceived::class);
    }
}