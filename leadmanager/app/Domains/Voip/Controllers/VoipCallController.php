<?php

namespace App\Domains\Voip\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Voip\Interfaces\VoipCallServiceInterface;
use App\Domains\Client\Services\ClientService;
use App\Domains\Shared\Exceptions\ClientNotFoundException;
use Illuminate\Http\Request;

class VoipCallController extends Controller
{

    private VoipCallServiceInterface $voipService;
    private ClientService $clientService;

    public function __construct(VoipCallServiceInterface $voipService, ClientService $clientService)
    {

        $this->voipService = $voipService;
        $this->clientService = $clientService;
    }

    /**
     * Initiates a VOIP call to a client.
     *
     * @param int $clientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTokenForClient(Request $request, int $id)
    {

        try {
            
            $client = $this->clientService->getClientById($id);
            if (empty($client->phone)) {
                throw new \Exception('Client does not have a phone number', 400);
            }
            $identity = $id ?? 'guest_'.uniqid();

            $token = $this->voipService->generateToken($identity);

            return response()->json(['token' => $token]);
        } catch (ClientNotFoundException $ce) {
            return response()->json(['error' => "Theres no client for the id provided"], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => "Could not initiate call {$e->getMessage()}"], 500);
        }
    }
}
