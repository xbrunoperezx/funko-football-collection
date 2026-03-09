<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - categories</title>
</head>
<body>
    <h1>Editar  categories </h1>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ $category->name }}" required>

        <label for="description">Descripción:</label>
        <textarea id="description" name="description" required>{{ $category->description }}</textarea>

        <button type="submit">Actualizar</button>
    </form>

</body>
</html>