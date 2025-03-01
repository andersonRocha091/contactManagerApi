<?php

namespace App\Domains\Client\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\Client\Services\ClientService;
use App\Domains\Client\Requests\CreateClientRequest;
use App\Domains\Client\Requests\UpdateClientRequest;
use App\Domains\Shared\Exceptions\ClientNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService) {
        $this->clientService = $clientService;
        // $this->middleware('auth');
    }

    public function create(CreateClientRequest $request){
        $client = $this->clientService->createClient($request->validated());
        return response()->json(['client' => $client]);
    }

    public function update(UpdateClientRequest $request, $id) {
        
        $updateFields = array_filter([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'picture' => $request->picture,
            'age' => $request->age
        ], function ($value) {
            return !is_null($value);
        });

        $response = $this->clientService->updateClient($id, $updateFields);
        return response()->json(['data' => $response]);
    }
    
    public function getAll(Request $request) {
        $filters = $request->only(['name', 'email', 'phone', 'city', 'state']);
        $perPage = $request->get('per_page', 15);
        $page = $request->get('page', 1);
        $response = $this->clientService->getAllClients($filters, $perPage, $page);
        return response()->json(['data' => $response]);
    }

    public function getClientById(Request $request, $id) {
        try {
            $client = $this->clientService->getClientById($id);
            return response()->json(['data' => $client]);
        } catch (ClientNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function delete(Request $request, $id) {
        try {
            $response = $this->clientService->deleteClient($id);
            return response()->json(['deleted' => $response]);
        } catch (ClientNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

}