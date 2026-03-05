<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funko;
use App\Models\Category;

class FunkoController extends Controller
{
    //  FUNCION Retorna la vista para listar Funkos
    public function index()
    {
        //Recuperar todos los funkos de la base de datos con su categoría
        $funkos = Funko::with('category')->get();

        return view('funkos.index', compact('funkos')); // Retorna la vista con los datos

    }

    // FUNCION  Retorna la vista para crear un Funko
    public function create()
    {
        // Obtener todas las categorías desde la base de datos
        $categories = Category::all();

        // Pasar las categorías a la vista
        return view('funkos.create', compact('categories')); // Retorna la vista para crear un Funko
    }

    // metodo FUNCION  para manejar los datos enviados desde el formulario
    public function store(Request $request)
    {
        //validar los datos del formulario
        $validatedData = $request->validate ([
            'name' => 'required|string|max:225',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Usar directamente el ID de la categoría enviado desde el formulario
        $validatedData['category_id'] = $request->input('category');

        //crear un nuevo Funko en la base de datos
        Funko::create($validatedData);

        // Redirigir al listado de Funkos con un mensaje de éxito
        return redirect()->route('funkos.index')->with('success', 'Funko creado exitosamente.');
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
