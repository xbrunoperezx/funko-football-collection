@extends('layouts.app')

@section('content')
    <h1>***Crear un nuevo  funkos***</h1>
    
    <form action="{{ route('funkos.store') }}" method="POST">
        @csrf
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="category">Categoría:</label>
        <select id="category" name="category" required>
            <option value="">Seleccione una categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <br>
        <label for="price">Precio:</label>
        <input type="number" id="price" name="price" required>
        <br>
        <button type="submit">Guardar</button>
    </form>

@endsection