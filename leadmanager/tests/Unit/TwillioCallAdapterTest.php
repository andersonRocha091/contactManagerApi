<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Voip\Infrastructure\Adapters\TwillioCallAdapter;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Illuminate\Support\Facades\Log;
use Mockery;
use Exception;

class TwillioCallAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Log::spy();
    }

    public function testInitiateCallSuccess()
    {
        $toPhoneNumber = '+1234567890';
        $instructionUrl = 'http://example.com/twiml';

        $clientMock = Mockery::mock(Client::class);
        $callsMock = Mockery::mock();
        $callInstanceMock = Mockery::mock(CallInstance::class);

        $clientMock->calls = $callsMock;
        $callsMock->shouldReceive('create')
            ->once()
            ->with($toPhoneNumber, Mockery::any(), ['url' => $instructionUrl])
            ->andReturn($callInstanceMock);

        $adapter = new TwillioCallAdapter();
        $adapter->setClient($clientMock);

        $result = $adapter->initiateCall($toPhoneNumber, $instructionUrl);

        $this->assertSame($callInstanceMock, $result);
    }

    public function testInitiateCallThrowsException()
    {
        $this->expectException(Exception::class);

        $toPhoneNumber = '+1234567890';
        $instructionUrl = 'http://example.com/twiml';

        $clientMock = Mockery::mock(Client::class);
        $callsMock = Mockery::mock();

        $clientMock->calls = $callsMock;
        $callsMock->shouldReceive('create')
            ->once()
            ->with($toPhoneNumber, Mockery::any(), ['url' => $instructionUrl])
            ->andThrow(new Exception('Test exception'));

        $adapter = new TwillioCallAdapter();
        $adapter->setClient($clientMock);

        try {
            $adapter->initiateCall($toPhoneNumber, $instructionUrl);
        } catch (Exception $e) {
            Log::shouldHaveReceived('error')
                ->with('Failed to initiate call: Test exception', Mockery::on(function ($data) use ($toPhoneNumber, $instructionUrl) {
                    return $data['toPhoneNumber'] === $toPhoneNumber && $data['instructionUrl'] === $instructionUrl;
                }))
                ->once();
            throw $e;
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}