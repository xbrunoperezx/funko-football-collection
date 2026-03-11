@extends('layouts.app')

@section('content')
    <h1>***Crear Nueva Categoría***</h1>
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" required></textarea>
        <br>
        <button type="submit">Guardar</button>
    </form>

@endsection