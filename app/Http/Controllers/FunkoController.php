<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FunkoController extends Controller
{
    //  FUNCION Retorna la vista para listar Funkos
    public function index()
    {
        return view('funkos.index'); // Retorna la vista para listar Funkos

    }

    // FUNCION  Retorna la vista para crear un Funko
    public function create()
    {
        return view('funkos.create'); // Retorna la vista para crear un Funko
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(string $id)
    {
        //
    }

   // FUNCION  Retorna la vista para editar un Funko
    public function edit(string $id)
    {
         return view('funkos.edit', compact('id')); // Retorna la vista para editar un Funko
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}
