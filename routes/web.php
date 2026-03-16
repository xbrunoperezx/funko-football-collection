<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FunkoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', [DashboardController::class, 'index']) 
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas CRUD protegidas
    Route::resource('funkos', FunkoController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
