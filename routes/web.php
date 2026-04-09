<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FunkoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});




// Ruta pública para la tienda (catálogo)
Route::get('/shop', [FunkoController::class, 'shop'])->name('shop');



    //rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    // Rutas CRUD protegidas
Route::middleware(['auth', 'admin'])->group(function() {
    Route::resource('funkos', FunkoController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'update']); //no se neecsitan crear ni eliminar pedidos en admin

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

});
 


// Rutas para el formulario , guardar pedido y de la confirmacion de compra
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/checkout/gracias/{order}', [OrderController::class , 'thanks'])->name('checkout.thanks');

require __DIR__.'/auth.php';
