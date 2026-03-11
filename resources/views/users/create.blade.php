@extends('layouts.app')

@section('content')
    <h1>***Crear un nuevo users***</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <button type="submit">Crear Usuario</button>
    </form>

    <a href="{{ route('users.index') }}">Volver al listado</a>

@endsection