<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Client\Controllers\ClientController;

Route::get('/client', [ClientController::class, 'index']);
Route::post('/client', [ClientController::class, 'create']);