<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoApiController;
use App\Http\Controllers\Api\ExternalDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/eventos', [EventoApiController::class, 'index']);
Route::get('/eventos/{id}', [EventoApiController::class, 'show']);
Route::post('/eventos', [EventoApiController::class, 'store']);
Route::put('/eventos/{id}', [EventoApiController::class, 'update']);
Route::delete('/eventos/{id}', [EventoApiController::class, 'destroy']);

// Ruta para obtener datos de la API externa
Route::get('/external-todos', [ExternalDataController::class, 'index']);