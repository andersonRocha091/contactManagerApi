<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Client\Controllers\ClientController;
use App\Domains\Auth\Infrastructure\Http\Controllers\AuthController;
use App\Domains\Voip\Controllers\VoipCallController;
use App\Domains\Webhook\Controllers\WebhookController;
use App\Http\Middleware\JwtMiddleware;

Route::get('/client', [ClientController::class, 'getAll']);
Route::get('/client/{id}', [ClientController::class, 'getClientById']);
Route::post('/client', [ClientController::class, 'create']);
Route::put('/client/{id}', [ClientController::class, 'update']);
Route::delete('/client/{id}', [ClientController::class, 'delete']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('webhook', [WebhookController::class, 'handler'])->withoutMiddleware(['auth']);
Route::post('voip/clients/{id}/call',[VoipCallController::class, 'getTokenForClient']);

Route::get('/twiml/minimal', function () {
    
    $destination = request('destination','+1234567890');

    $twiml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Response>
        <Dial>{$destination}</Dial>
    </Response>
    XML;
    return response($twiml, 200, ['Content-Type' => 'text/xml']);
})->name('twiml.minimal');