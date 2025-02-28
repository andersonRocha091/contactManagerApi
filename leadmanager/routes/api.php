<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Client\Controllers\ClientController;

Route::post('/client', [ClientController::class, 'create']);