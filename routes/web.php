<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Ruta principal para mostrar la vista
Route::get('/', [HomeController::class, 'index']);
