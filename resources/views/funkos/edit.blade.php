@extends('layouts.app')

@section('content')
    <h1 class="text-center my-4">***Editar Funko***</h1>

    <form action="{{ route('funkos.update', $funko->id) }}" method="POST" class="w-50 mx-auto">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $funko->name }}" placeholder="Ingrese el nombre del funko" required>
        </div>

        <div class="form-group mb-3">
            <label for="category" class="form-label">Categoría:</label>
            <select id="category" name="category" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $funko->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="price" class="form-label">Precio:</label>
            <input type="number" id="price" name="price" class="form-control" value="{{ $funko->price }}" placeholder="Ingrese el precio" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('funkos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection