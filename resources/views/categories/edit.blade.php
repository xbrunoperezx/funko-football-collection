@extends('layouts.app')

@section('content')
    <h1 class="text-center my-4">***Editar Categoría***</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="w-50 mx-auto">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}" placeholder="Ingrese el nombre de la categoría" required>
        </div>

        <div class="form-group mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Ingrese una descripción" required>{{ $category->description }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

@endsection
