<?php

namespace App\Domains\Webhook\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domains\Webhook\Events\WebhookReceived;

class WebhookController extends Controller {

    public function handler(Request $request) {

        $data = $request->all();

        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255',
            'phone'=> 'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'city'=>'nullable|string|max:255',
            'state'=>'nullable|string|max:40',
            'zip'=>'nullable|string|max:9',
            'picture'=>'nullable|string',
            'age' => 'required|integer|min:1'
        ]);

         // Dispatch event
         WebhookReceived::dispatch($validated);

         return response()->json(['message' => 'Webhook received'], 200);
    }
}