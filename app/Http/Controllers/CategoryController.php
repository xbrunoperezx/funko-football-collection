<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // FUNCION retorna la vista para listar categories
    public function index()
    {
        //Obtener todas las categorias de la base de datos
        $categories = Category::all();


        //retorna la vista para listar categories
        return view('categories.index', compact('categories')); 
    }

    // FUNCION Retorna la vista para crear un catgories Formulario
    public function create()
    {
        //Retorna la vista para crear un categories
        return view('categories.create'); 
    }

    //Metodo para guardar una nueva categoria
    public function store(Request $request)
    {
        //Validarmos los datos del formulario
        $validatedData = $request->Validate([
            'name' => 'requiered|string|max:255',
            'description' => 'requiered|string',
        ]);

        //creamos la categoria en la base de atos
        Category::create($validatedData);

        //redirigimos al indice con mensaje de exito
        return redirect()->route('categories.index')->with('success', 'Categoria creada exitosamente');
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
