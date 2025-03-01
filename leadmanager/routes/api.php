<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Client\Controllers\ClientController;

Route::get('/client', [ClientController::class, 'getAll']);
Route::get('/client/{id}', [ClientController::class, 'getClientById']);
Route::post('/client', [ClientController::class, 'create']);
Route::put('/client/{id}', [ClientController::class, 'update']);
Route::delete('/client/{id}', [ClientController::class, 'delete']);