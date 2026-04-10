<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// API routes untuk proses login/register (backend logic)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
}); 