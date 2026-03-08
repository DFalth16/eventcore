<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ParticipanteController;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
Route::get('/logout',   [AuthController::class, 'logout']);

// ── Rutas protegidas (auth.admin) ─────────────────────────────────────────────
Route::middleware('auth.admin')->group(function () {
    Route::get('/',          fn() => redirect('/dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Eventos (CRUD + inscritos)
    Route::get('/eventos',                   [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/crear',             [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos',                  [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{id}/editar',       [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}',              [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}',           [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::get('/eventos/{id}/inscritos',    [EventoController::class, 'inscritosMostrar'])->name('eventos.inscritos');
    Route::post('/eventos/{id}/inscritos',   [EventoController::class, 'inscritosStore'])->name('eventos.inscritos.store');

    // Sedes
    Route::get('/sedes',             [SedeController::class, 'index'])->name('sedes.index');
    Route::get('/sedes/crear',       [SedeController::class, 'create'])->name('sedes.create');
    Route::post('/sedes',            [SedeController::class, 'store'])->name('sedes.store');
    Route::get('/sedes/{id}/editar', [SedeController::class, 'edit'])->name('sedes.edit');
    Route::put('/sedes/{id}',        [SedeController::class, 'update'])->name('sedes.update');
    Route::delete('/sedes/{id}',     [SedeController::class, 'destroy'])->name('sedes.destroy');

    // Usuarios
    Route::get('/usuarios',             [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear',       [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios',            [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}',        [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}',     [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Participantes
    Route::get('/participantes',             [ParticipanteController::class, 'index'])->name('participantes.index');
    Route::get('/participantes/crear',       [ParticipanteController::class, 'create'])->name('participantes.create');
    Route::post('/participantes',            [ParticipanteController::class, 'store'])->name('participantes.store');
    Route::get('/participantes/{id}/editar', [ParticipanteController::class, 'edit'])->name('participantes.edit');
    Route::put('/participantes/{id}',        [ParticipanteController::class, 'update'])->name('participantes.update');
    Route::delete('/participantes/{id}',     [ParticipanteController::class, 'destroy'])->name('participantes.destroy');
});
