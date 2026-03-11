@extends('layouts.app')

@section('content')
    <h1>***Listado de funkos***</h1>
    <a href="{{ route('funkos.create') }}">Crear Nuevo Funko</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($funkos as $funko)
            <tr>
                <td>{{ $funko->id }}</td>
                <td>{{ $funko->name }}</td>
                <td>{{ $funko->category->name }}</td> <!-- Mostrar el nombre de la categoría -->
                <td>{{ $funko->price }}</td>
                <td>
                    <a href="{{ route('funkos.edit', $funko->id) }}">Editar</a>
                    <form action="{{ route('funkos.destroy', $funko->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection