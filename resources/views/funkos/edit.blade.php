@extends('layouts.app')


@section('content')
    <h1>***Editar Funko***</h1>
    
    <form action="{{ route('funkos.update', $funko->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ $funko->name }}" required>
        <br>
        <label for="category">Categoría:</label>
        <select id="category" name="category" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $funko->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <br>
        <label for="price">Precio:</label>
        <input type="number" id="price" name="price" value="{{ $funko->price }}" required>
        <br>
        <button type="submit">Actualizar</button>
    </form>

@endsection