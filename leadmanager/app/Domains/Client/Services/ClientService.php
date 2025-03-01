<?php

namespace App\Domains\Client\Services;

use App\Domains\Client\Repositories\ClientRepositoryInterface;
use App\Domains\Client\Entities\Client;
use App\Domains\Shared\Events\ClientCreated;
use Illuminate\Support\Facades\Event;
use App\Domains\Shared\Exceptions\ClientNotFoundException;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function createClient(array $data)
    {
        $this->validateClientData($data, null, 'create');

        // Create client
        // Dispatch event for potential webhooks
        $client = $this->clientRepository->create($data); 
        // Event::dispatch(new ClientCreated($client));
        return $client;
    }

    public function updateClient(int $clientId, array $data)
    {   
        $this->validateClientData($data, $clientId, 'update');

        // Update client
        return $this->clientRepository->update($clientId, $data);
    }

    public function deleteClient(?int $clientId): bool
    {
        // Delete client
        $this->validateClientData([], $clientId, 'delete');
        return $this->clientRepository->delete($clientId);
    }

    public function getClientById(int $clientId)
    {
        $client = $this->clientRepository->getById($clientId);
        if (!$client) {
            throw new ClientNotFoundException("Client with ID {$clientId} not found.");
        }
        return $client;
    }

    public function getAllClients(array $filters = [], int $perPage = 15, int $page = 1): array
    {
        // Get all clients
        $clients = $this->clientRepository->getAll($filters, $perPage, $page);
        return $clients->toArray();
    }

    protected function validateClientData(array $data, ?int $clientId, string $operation)
    {
        
        switch ($operation) {
            case 'create':
                if (empty($data['name']) || empty($data['email'])) {
                    throw new \InvalidArgumentException('Name and email are required.');
                }
                break;
            case 'update':
                if (empty($clientId)) {
                    throw new \InvalidArgumentException('ID is required for update.');
                }
                if (empty($data['name']) || empty($data['email'])) {
                    throw new \InvalidArgumentException('Name and email are required.');
                } 
                break;
            case 'delete':
                if (empty($clientId)) {
                    throw new \InvalidArgumentException('ID is required for delete.');
                }   
            default:
                # code...
                break;
        }
    }
}