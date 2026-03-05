<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // FUNCION retorna la vista para listar categories
    public function index()
    {
        return view('categories.index'); //retorna la vista para listar categories
    }

    // FUNCION Retorna la vista para crear un catgories
    public function create()
    {
        return view('categories.create'); // Retorna la vista para crear un categories
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(string $id)
    {
        //
    }

    // FUNCION Retorna la vista para editar un categories
    public function edit(string $id)
    {
        return view('categories.edit', compact('id')); // Retorna la vista para editar un categories
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
