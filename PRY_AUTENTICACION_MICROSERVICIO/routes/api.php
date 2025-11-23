<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;

// Rutas PÚBLICAS de autenticación
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login',    [AuthController::class, 'login']);

// Rutas PROTEGIDAS con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint que usará el microservicio de Posts para validar el token
    Route::get('/validate-token', [AuthController::class, 'validateToken']);

    // Logout global (revoca todos los tokens del usuario autenticado)
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de usuarios
    Route::get('/users',         [UserController::class, 'index']);
    Route::post('/users',        [UserController::class, 'store']);
    Route::get('/users/{id}',    [UserController::class, 'show']);
    Route::put('/users/{id}',    [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
