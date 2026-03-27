<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Funko;

class OrderController extends Controller
{
  public function store(Request $request) {
        // 1. validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'cart' => 'required|json',
        ]);

        // 2. Decodificar el carrito(viene como JSON desde el formulario de Front)
        $cart = json_decode($validated['cart'], true);

        // 3. Calcular el total del producto
        $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
        });

        // 4. Guardar el pedido en la base de datos
        $order = Order::create([
            'user_id' => auth()->id(), //null si no esta logeado el user
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' =>$validated['address'],
            'total' => $total,
            'status' => 'pending',
        ]);

        // 5. Descontar stock de cadafunko creado
        foreach ($cart as $item) {
            $funko = Funko::find($item['id']);
            if($funko && $funko ->stock >= $item['quantity']) {
                $funko->decrement('stock', $item['quantity']);
            }
        }

        // 6. Redirigir a la pagina de confirmacion
        return redirect()->route('checkout.thanks', ['order' => $order->id]);
    }

    public function thanks($order) {
        return view('shop.thankyou', ['orderId' => $order]);
    }
}
