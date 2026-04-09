<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()?->role !== 'admin') {  //proteje al propietario si no hay sesion
             return redirect('/shop');  // si no es admin-> fuera redirige a la tienda
        } 
        
        return $next($request);   //si es admin->adelante  entra en pànel
    }
       
    
}
