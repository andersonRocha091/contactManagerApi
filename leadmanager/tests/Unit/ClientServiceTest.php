<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domains\Client\Services\ClientService;
use App\Domains\Client\Repositories\ClientRepositoryInterface;
use App\Domains\Client\Domain\Entities\Client;
use Mockery;
use InvalidArgumentException;
use App\Domains\Shared\Exceptions\ClientNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientServiceTest extends TestCase
{
    protected $clientService;
    protected $clientRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientRepository = Mockery::mock(ClientRepositoryInterface::class);
        $this->clientService = new ClientService($this->clientRepository);
    }

    public function testCreateClient()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip' => '12345',
            'picture' => 'path/to/picture.jpg',
            'age' => 30
        ];

        $this->clientRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new Client($data));

        $client = $this->clientService->createClient($data);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('John Doe', $client->name);
    }

    public function testCreateClientValidationFails()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name and email are required.');

        $data = [
            'email' => 'john.doe@example.com',
        ];

        $this->clientService->createClient($data);
    }

    public function testUpdateClient()
    {
        $clientId = 1;
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
        ];

        $this->clientRepository->shouldReceive('update')
            ->once()
            ->with($clientId, $data)
            ->andReturn(new Client($data));

        $client = $this->clientService->updateClient($clientId, $data);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Jane Doe', $client->name);
    }

    public function testUpdateClientNonExistent()
    {
        $clientId = 999;
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
        ];

        $this->clientRepository->shouldReceive('update')
            ->once()
            ->with($clientId, $data)
            ->andThrow(new ModelNotFoundException);

        $this->expectException(ModelNotFoundException::class);

        $this->clientService->updateClient($clientId, $data);
    }

    public function testUpdateClientValidationFails()
    {
        $clientId = 1;
        $data = [
            'email' => 'jane.doe@example.com',
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name and email are required.');

                $this->clientService->updateClient($clientId, $data);
    }

    public function testDeleteClient()
    {
        $clientId = 1;

        $this->clientRepository->shouldReceive('delete')
            ->once()
            ->with($clientId)
            ->andReturn(true);

        $result = $this->clientService->deleteClient($clientId);

        $this->assertTrue($result);
    }

    public function testDeleteClientNonExistent()
    {
        $clientId = 999;

        $this->clientRepository->shouldReceive('delete')
            ->once()
            ->with($clientId)
            ->andThrow(new ModelNotFoundException);

        $this->expectException(ModelNotFoundException::class);

        $this->clientService->deleteClient($clientId);
    }

    public function testDeleteClientNoId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID is required for delete.');

        $this->clientService->deleteClient(null);
    }

    public function testGetClientById()
    {
        $clientId = 1;
        $clientData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ];

        $this->clientRepository->shouldReceive('getById')
            ->once()
            ->with($clientId)
            ->andReturn(new Client($clientData));

        $client = $this->clientService->getClientById($clientId);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('John Doe', $client->name);
    }

    public function testGetClientByIdNotFound()
    {
        $clientId = 999;

        $this->clientRepository->shouldReceive('getById')
            ->once()
            ->with($clientId)
            ->andReturn(null);

        $this->expectException(ClientNotFoundException::class);

        $this->clientService->getClientById($clientId);
    }

    public function testGetAllClients()
    {
        $filters = [];
        $perPage = 15;
        $page = 1;
        $clientsData = [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Doe', 'email' => 'jane.doe@example.com'],
        ];

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($clientsData, count($clientsData), $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        $this->clientRepository->shouldReceive('getAll')
            ->once()
            ->with($filters, $perPage, $page)
            ->andReturn($paginator);

        $clients = $this->clientService->getAllClients($filters, $perPage, $page);

        $this->assertIsArray($clients);
        $this->assertCount(2, $clients['data']);
        $this->assertEquals('John Doe', $clients['data'][0]['name']);
    }
}