<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::middleware('auth.micro')->group(function () {
    Route::get('/posts',        [PostController::class, 'index']);
    Route::post('/posts',       [PostController::class, 'store']);
    Route::get('/posts/{id}',   [PostController::class, 'show']);
    Route::put('/posts/{id}',   [PostController::class, 'update']);
    Route::delete('/posts/{id}',[PostController::class, 'destroy']);
});