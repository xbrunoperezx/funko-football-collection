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
        $validatedData = $request->validate ([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
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
        // Buscar la categoría por su ID
        $category = Category::findOrFail($id);

        // Retornar la vista de edición con los datos de la categoría
        return view('categories.edit', compact('category'));
    }

    
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Buscar la categoría por su ID
        $category = Category::findOrFail($id);

        // Actualizar los datos de la categoría
        $category->update($validatedData);

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    
    public function destroy(string $id)
    {
        // Buscar la categoria por su ID
        $category = Category::findOrFail($id);

        // Eliminar la categoria
        $category->delete();

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
