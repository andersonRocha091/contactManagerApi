<?php

namespace App\Domains\Client\Repositories;

use App\Domains\Client\Domain\Entities\Client;

interface ClientRepositoryInterface {

    public function getAll(array $filters = [], int $perPage, int $page);
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);

}