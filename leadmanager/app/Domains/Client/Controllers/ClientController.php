<?php

namespace App\Domains\Client\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\Client\Services\ClientService;
use App\Domain\Client\Requests\UpdateClientRequest;
use App\Domains\Client\Requests\CreateClientRequest;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService) {
        $this->clientService = $clientService;
        // $this->middleware('auth');
    }

    public function index()
    {
        return response()->json(['message' => 'Hello, world!']);
    }

    public function create(CreateClientRequest $request){
        $client = $this->clientService->createClient($request->validated());
        return response()->json(['client' => $client]);
    }
}