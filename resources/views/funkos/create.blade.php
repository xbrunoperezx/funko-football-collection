<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nuevo Funko
        </h2>
    </x-slot>
    <div class="py-6">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('funkos.store') }}" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Ingrese el nombre del funko" required>
            </div>
            <div class="form-group mb-3">
                <label for="category" class="form-label">Categoría:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="price" class="form-label">Precio:</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="Ingrese el precio" required>
            </div>
            <div class="form-group mb-3">
                <label for="stock" class="form-label">Stock (unidades disponibles):</label>
                <input type="number" id="stock" name="stock" class="form-control" placeholder="0" min="0" value="0" required>
            </div>
            <div class="form-group mb-3">
                <label for="image" class="form-label">Imagen:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('funkos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>