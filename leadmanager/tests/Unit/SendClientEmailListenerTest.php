<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Client\Listeners\SendClientEmailListener;
use App\Domains\Shared\Events\ClientCreatedSuccesfully;
use App\Domains\Client\Infrastructure\Jobs\SendClientEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Mockery;
use App\Domains\Client\Domain\Entities\Client;

class SendClientEmailListenerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Log::spy();
    }

    public function testHandleDispatchesJob()
    {
        $client = new Client([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $event = new ClientCreatedSuccesfully($client);
        $listener = new SendClientEmailListener();

        $listener->handle($event);

        Queue::assertPushed(SendClientEmailJob::class, function ($job) use ($client) {
            return $job->getClient()->email === $client->email;
        });

        Log::shouldHaveReceived('info')->once();
    }

    public function testHandleLogsErrorOnException()
    {
        $client = new Client([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $event = new ClientCreatedSuccesfully($client);

        $listener = $this->getMockBuilder(SendClientEmailListener::class)
                        ->onlyMethods(['dispatchJob'])
                        ->getMock();
        
        $listener->method('dispatchJob')->willThrowException(new \Exception('Test exception'));

        $listener->handle($event);
        
        Log::shouldHaveReceived('critical')
        ->with('Failed to dispatch SendClientEmailJob: Test exception', Mockery::on(function ($data) use ($client) {
            return $data['client']->email === $client->email;
        }))
        ->once();

        // Explicit PHPUnit assertion to avoid the "risky test" warning
        $this->assertTrue(true);

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}