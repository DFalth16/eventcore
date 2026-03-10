<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoApiController;
use App\Http\Controllers\Api\ExternalDataController;
use App\Http\Controllers\Api\ApiAuthController;

Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/external-todos', [ExternalDataController::class, 'index']);

// Rutas Protegidas
Route::middleware('api.token')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    Route::get('/eventos', [EventoApiController::class, 'index']);
    Route::get('/eventos/{id}', [EventoApiController::class, 'show']);
    Route::post('/eventos', [EventoApiController::class, 'store']);
    Route::put('/eventos/{id}', [EventoApiController::class, 'update']);
    Route::delete('/eventos/{id}', [EventoApiController::class, 'destroy']);
});
