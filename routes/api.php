<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::get('/ping', fn () => response()->json(['ok' => true]));

Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // optional

// Public reads:
Route::get('/posts',        [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// Protected (Bearer token required):
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',       [AuthController::class, 'me']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    Route::post('/posts',           [PostController::class, 'store']);
    Route::put('/posts/{post}',     [PostController::class, 'update']);
    Route::patch('/posts/{post}',   [PostController::class, 'update']);
    Route::delete('/posts/{post}',  [PostController::class, 'destroy']);
});

