<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nueva Categoría
        </h2>
    </x-slot>
    <div class="py-6">
        <form action="{{ route('categories.store') }}" method="POST" class="w-50 mx-auto">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Ingrese el nombre de la categoría" required>
            </div>
            <div class="form-group mb-3">
                <label for="description" class="form-label">Descripción:</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Ingrese una descripción" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>