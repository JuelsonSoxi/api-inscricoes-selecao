<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas de autenticação
Route::prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rotas públicas
Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/programs/{id}', [ProgramController::class, 'show']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/me', [AuthController::class, 'me']);
    
    // Programs management (apenas para demonstração - em produção teria roles/permissions)
    Route::post('/programs', [ProgramController::class, 'store']);
    Route::put('/programs/{id}', [ProgramController::class, 'update']);
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy']);
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/my-applications', [ApplicationController::class, 'myApplications']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
});