<?php

namespace App\Domains\Client\Services;

use App\Domains\Client\Repositories\ClientRepositoryInterface;
use App\Domains\Client\Entities\Client;
use Illuminate\Support\Facades\Event;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function createClient(array $data)
    {
        // Validate data
        $this->validateClientData($data);

        // Create client
        // Dispatch event for potential webhooks
        $client = $this->clientRepository->create($data); 
        Event::dispatch(new LeadCreated($lead));
        return $client;
    }

    public function updateClient(int $clientId, array $data): Client
    {
        // Validate data
        $this->validateClientData($data);

        // Update client
        return $this->clientRepository->update($clientId, $data);
    }

    public function deleteClient(int $clientId): bool
    {
        // Delete client
        return $this->clientRepository->delete($clientId);
    }

    public function getClientById(int $clientId): ?Client
    {
        // Get client by ID
        return $this->clientRepository->getById($clientId);
    }

    public function getAllClients(array $filters = []): array
    {
        // Get all clients
        return $this->clientRepository->getAll($filters);
    }

    protected function validateClientData(array $data)
    {
        // Add your validation logic here
        // For example, check if required fields are present
        if (empty($data['name']) || empty($data['email'])) {
            throw new \InvalidArgumentException('Name and email are required.');
        }
    }
}