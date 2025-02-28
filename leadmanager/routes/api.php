<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\UserController;

Route::get('hello', [UserController::class, 'index']);