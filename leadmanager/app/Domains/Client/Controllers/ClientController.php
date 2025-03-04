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
        
        $updateFields = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->has('mobile') ? $request->mobile : null,
            'district' => $request->has('district') ? $request->district : null,
            'phone' => $request->has('phone') ? $request->phone : null,
            'address' => $request->has('address') ? $request->address : null,
            'city' => $request->has('city') ? $request->city : null,
            'state' => $request->has('state') ? $request->state : null,
            'zip' => $request->has('zip') ? $request->zip : null,
            'picture' => $request->has('picture') ? $request->picture : null,
            'age' => $request->has('age') ? $request->age : null
        ];
        try {
            $response = $this->clientService->updateClient($id, $updateFields, $request);
            return response()->json(['data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not updated. An unexpected error occurred.'], 500);
        }
       
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