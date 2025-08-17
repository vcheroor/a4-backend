<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;

Route::get('/', fn () => redirect('/admin'));

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('admin')->group(function () { 

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/create', [PostController::class, 'create']);
    Route::post('/posts/save', [PostController::class, 'save']);
    Route::get('/posts/edit/{post}', [PostController::class, 'edit']);
    Route::get('/posts/delete/{post}', [PostController::class, 'delete']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/create', [CategoryController::class, 'create']);
    Route::post('/categories/save', [CategoryController::class, 'save']);
    Route::get('/categories/edit/{category}', [CategoryController::class, 'edit']);
    Route::get('/categories/delete/{category}', [CategoryController::class, 'delete']);
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});