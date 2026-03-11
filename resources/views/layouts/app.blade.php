<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Funko Football Collection')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <header>
        <h1> Estas en el Layout de Funko Football Collection [PRUEBA]</h1>
        <nav>
            <a href="{{ route('funkos.index') }}">Funkos</a>
            <a href="{{ route('categories.index') }}">Categorias</a>
            <a href="{{ route('users.index') }}">Usuarios</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmarButtonText: 'Aceptar'
            });
        </script>
    @endif
    
    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmarButtonText: 'Aceptar'
            });
        </script>
    @endif
</body>
</html>