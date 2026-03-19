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
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:225',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,bmp|max:4096'
        ]);

        $validatedData['category_id'] = $request->input('category');

        // Manejar la imagen si se sube
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Detectar la categoría
            $category = $request->input('category');
            $categoryFolder = strtolower(str_replace(' ', '-', $category)); // Ejemplo: "Era moderna" -> "era-moderna"

            // Crear la carpeta si no existe
            $destinationPath = public_path('images/funkos/' . $categoryFolder);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Mover la imagen
            $image->move($destinationPath, $imageName);

            // Guardar la ruta relativa
            $validatedData['image_path'] = 'images/funkos/' . $categoryFolder . '/' . $imageName;
        }

        Funko::create($validatedData);

        return redirect()->route('funkos.index')->with('success', 'Funko creado exitosamente.');
    }

    
    public function show(string $id)
    {
        //
    }

        // Método para mostrar la vista pública de la tienda (catálogo)
        public function shop()
        {
            // Recuperar todos los funkos con su categoría
            $funkos = Funko::with('category')->get();
            return view('shop.index', compact('funkos'));
        }

   // FUNCION  Retorna la vista para editar un Funko en el formulario
    public function edit(string $id)
    {
        //Buscamos el funko por su ID
        $funko = Funko::findOrFail($id);

        //Obtenemos todas las categorias para el select
        $categories = Category::all();

        //Retornamos a la visata de edicion con los datos del Funko y la categoria
        return view('funkos.edit', compact('funko', 'categories')); 
    }

    // Método para actualizar un Funko existente
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:225',
            'category' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,bmp|max:4096'
        ]);

        // Buscar el Funko por su ID
        $funko = Funko::findOrFail($id);

        // Actualizar los datos del Funko
        $updateData = [
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category'],
            'price' => $validatedData['price'],
        ];

        // Manejar la imagen si se sube
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/funkos'), $imageName);
            $updateData['image_path'] = 'images/funkos/' . $imageName;
        }

        $funko->update($updateData);

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('funkos.index')->with('success', 'Funko actualizado exitosamente.');
    }

    
    public function destroy(string $id)
    {
        // Buscar el Funko por su ID y eliminarlo
        $funko = Funko::findOrFail($id);
        $funko->delete();

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('funkos.index')->with('success', 'Funko eliminado exitosamente.');
    }
}
