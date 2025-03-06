<?php

namespace App\Domains\Webhook\Controllers;

use App\Domains\Shared\Exceptions\ClientCreationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domains\Webhook\Events\WebhookReceived;

class WebhookController extends Controller
{

    public function handler(Request $request)
    {

        try {

            $data = $request->all();

            $isInitalRequest = $this->isInitialTokenRequest($data);
            if ($isInitalRequest) {
                return response()->json($request->input('token'), 200);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:40',
                'zip' => 'nullable|string|max:9',
                'picture' => 'nullable|string',
                'age' => 'required|integer|min:1'
            ]);

            event(new WebhookReceived($validated));

        } catch (ClientCreationException $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Internal server error'
            ], 500);
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }

    private function isInitialTokenRequest(array $data = []): bool {
        
        if(empty($data)) {
            return false;
        }

        return isset($data['token']) && $data['validToken']; 
    }
}
