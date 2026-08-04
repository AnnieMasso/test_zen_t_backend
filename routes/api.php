<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\agentController;
use App\Http\Controllers\API\userController;

Route::prefix('auth/users')->group(function (): void {
    Route::post('/register-user', [userController::class, 'register']);
    Route::post('/login', [userController::class, 'login']);

    Route::middleware('auth:users-api')->group(function (): void {
        Route::get('/infos', [userController::class, 'infos']);
        Route::put('/change-password', [userController::class, 'changePassword']);
        Route::put('/profil', [userController::class, 'updateNom']);
        Route::post('/store_transaction', [transactionController::class, 'store']);
        Route::delete('/logout', [userController::class, 'deconnexion']);
        Route::put('/annuler_transaction', [agentController::class, 'annuler_transaction']);
        // Route::post('/')
    });

});

Route::prefix('auth/agents')->group(function (): void {
    Route::post('/register-agent', [agentController::class, 'register']);
    Route::post('/login', [agentController::class, 'login']);

    Route::middleware('auth:agents-api')->group(function (): void {
        Route::get('/infos', [agentController::class, 'infos']);
        Route::post('/change-password', [agentController::class, 'changePassword']);
        Route::put('/profil', [agentController::class, 'updateNom']);
        Route::post('/logout', [agentController::class, 'deconnexion']);
        Route::get('/dashboard', [agentController::class, 'dashboard']);
        Route::put('/annuler_transaction', [agentController::class, 'annuler_transaction']);
        Route::put('/valider_transaction', [agentController::class, 'valider_transaction']);
        Route::put('/confirmer_remboursement', [agentController::class, 'confirmer_remboursement']);
        Route::get('/transactions', [AgentController::class, 'transactions']);
    });

});