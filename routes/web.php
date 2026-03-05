<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FunkoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

Route::get('/test', function () {
    return "Todo funciona correctamente 🚀";
});

// Ruta para la vista principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de tipo resource para las entidades principales
Route::resource('funkos', FunkoController::class);
Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);
