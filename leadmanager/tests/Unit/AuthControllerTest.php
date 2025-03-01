<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Auth\Infrastructure\Http\Controllers\AuthController;
use App\Domains\Auth\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Mockery;
use App\Domains\Auth\Domain\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthControllerTest extends TestCase
{
    protected $userService;
    protected $jwtAuth;
    protected $auth;
    protected $authController;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->userService = Mockery::mock(UserService::class);
        $this->jwtAuth = Mockery::mock('alias:Tymon\JWTAuth\Facades\JWTAuth'); // Mock the facade
        $this->auth = Mockery::mock('overload:Illuminate\Support\Facades\Auth');
    
        $this->authController = new AuthController($this->userService, $this->jwtAuth, $this->auth);
    }

    public function testRegister()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password'
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        Validator::shouldReceive('make')->once()->andReturn($validator);

        $user = new User([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'hashed_password'
        ]);

        $this->userService->shouldReceive('createUser')
            ->once()
            ->with($request->all())
            ->andReturn($user);

        $this->jwtAuth->shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andReturn('token');

        $response = $this->authController->register($request);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('user', $response->getData(true));
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    public function testLogin()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'john.doe@example.com',
            'password' => 'password'
        ]);

        $credentials = $request->only('email', 'password');

        $this->auth->shouldReceive('guard->attempt')
            ->once()
            ->with($credentials)
            ->andReturn('token');

        $this->auth->shouldReceive('guard->factory->getTTL')
            ->once()
            ->andReturn(60);

        $response = $this->authController->login($request);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('access_token', $response->getData(true));
        $this->assertArrayHasKey('token_type', $response->getData(true));
        $this->assertArrayHasKey('expires_in', $response->getData(true));
    }

    public function testLoginInvalidCredentials()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'john.doe@example.com',
            'password' => 'wrong_password'
        ]);

        $credentials = $request->only('email', 'password');

        $this->auth->shouldReceive('guard->attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $response = $this->authController->login($request);

        $this->assertEquals(401, $response->status());
        $this->assertArrayHasKey('error', $response->getData(true));
    }

    public function testGetUser()
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com'
        ]);

        $this->jwtAuth->shouldReceive('parseToken->authenticate')
            ->once()
            ->andReturn($user);

        $response = $this->authController->getUser();

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('user', $response->getData(true));
    }

    public function testGetUserNotFound()
    {
        $this->jwtAuth->shouldReceive('parseToken->authenticate')
            ->once()
            ->andReturn(false);

        $response = $this->authController->getUser();

        $this->assertEquals(404, $response->status());
        $this->assertArrayHasKey('error', $response->getData(true));
    }

    public function testLogout()
    {
        $this->jwtAuth->shouldReceive('invalidate')
            ->once()
            ->andReturn(true);

        $this->jwtAuth->shouldReceive('getToken')
            ->once()
            ->andReturn('token');

        $response = $this->authController->logout();

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}