<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticationController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthenticationController::class, 'user']);
