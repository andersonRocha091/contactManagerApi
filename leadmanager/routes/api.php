<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Client\Controllers\ClientController;
use App\Domains\Auth\Infrastructure\Http\Controllers\AuthController;
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