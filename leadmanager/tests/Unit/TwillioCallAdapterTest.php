<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Mocks\JWTMock;
use App\Domains\Voip\Infrastructure\Adapters\TwillioCallAdapter;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Exception;
use Mockery;

class TwillioCallAdapterTest extends TestCase
{
    private $config;
    private $jwtMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = [
            'sid' => 'test_sid',
            'apiKey' => 'test_apiKey',
            'apiSecret' => 'test_apiSecret',
            'twilioNumber' => 'test_twilioNumber',
            'appSid' => 'test_appSid'
        ];
        
        $this->jwtMock = new JWTMock();
    }

    public function testGenerateTokenSuccess()
    {
        $identity = 'test_identity';

        $adapter = Mockery::mock(TwillioCallAdapter::class)->makePartial();
        $adapter->setConfig($this->config);

        $adapter->shouldAllowMockingProtectedMethods();

        $adapter->shouldReceive('isValidToken')
            ->once()
            ->andReturn(['status' => 'valid']);

        $token = $adapter->generateToken($identity);

        $this->assertNotEmpty($token);
    }

    public function testGenerateTokenInvalid()
    {
        $identity = 'test_identity';

        $adapter = Mockery::mock(TwillioCallAdapter::class)->makePartial();
        $adapter->setConfig($this->config);

        $adapter->shouldAllowMockingProtectedMethods();

        $adapter->shouldReceive('isValidToken')
            ->once()
            ->andReturn(['status' => 'invalid']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Couldnt create a token for the credentials provided');
        
        $adapter->generateToken($identity);
    }

    public function testGenerateTokenThrowsException()
    {
        $identity = 'test_identity';

        $adapter = Mockery::mock(TwillioCallAdapter::class)->makePartial();
        $adapter->setConfig($this->config);

        $adapter->shouldAllowMockingProtectedMethods();

        $adapter->shouldReceive('isValidToken')
            ->once()
            ->andThrow(new Exception('Test exception'));

        $this->expectException(Exception::class);
        
        $adapter->generateToken($identity);
    }

    public function testIsValidTokenSuccess()
    {
        $token = 'test_token';
        $decoded = (object) ['sub' => 'test_identity'];
        
        $this->jwtMock->setReturnValue($decoded);

        $adapter = new TwillioCallAdapter($this->jwtMock);
        $adapter->setConfig($this->config);

        $result = $adapter->isValidToken($token);

        $this->assertEquals('valid', $result['status']);
        $this->assertEquals((array) $decoded, $result['data']);
    }

    public function testIsValidTokenInvalid()
    {
        $token = 'test_token';
        
        $this->jwtMock->setThrowException(true);

        $adapter = new TwillioCallAdapter($this->jwtMock);
        $adapter->setConfig($this->config);

        $result = $adapter->isValidToken($token);

        $this->assertEquals('invalid', $result['status']);
        $this->assertEquals('Invalid token', $result['error']);
    }

    public function testIsValidTokenEmpty()
    {
        $adapter = new TwillioCallAdapter($this->jwtMock);
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No data found on token');

        $adapter->isValidToken('');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}