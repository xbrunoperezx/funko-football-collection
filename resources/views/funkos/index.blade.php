<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Funkos</title>
</head>
<body>
    <h1>Listado de funkos </h1>
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
</body>
</html>