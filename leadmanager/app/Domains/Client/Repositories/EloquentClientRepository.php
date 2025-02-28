<?php

use App\Domains\Client\Domain\Entities\Client;
use App\Domains\Client\Repositories\ClientRepositoryInterface;

class EloquentClientRepository implements ClientRepositoryInterface {

    protected $model;

    public function __construct(Client $client) {
        $this->model = $client;
    }

    public function getAll(array $filters = []) {
        $query = $this->model->newQuery();

        if (isset($filters['age'])) {
            $query->where('age', $filters['age']);
        }

        if (isset($filters['zip'])) {
            $query->where('zip', $filters['zip']);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        return $query->paginate(15);
    }

    public function getById(int $id) {
        return $this->model->findOrFail($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }
    
    public function update(int $id, array $data) {
        $client = $this->getById($id);
        $client->update($data);
        return $client;
    }

    public function delete(int $id) {
        $client = $this->getById($id);
        $client->delete();
        return $client->delete();
    }
   
}