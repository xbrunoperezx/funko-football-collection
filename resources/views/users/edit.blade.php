@extends('layouts.app')


@section('content')
    <h1>***Editar users***</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" required>

        <label for="password">Contraseña (dejar en blanco para no cambiar):</label>
        <input type="password" id="password" name="password">

        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
        </select>

        <button type="submit">Actualizar Usuario</button>
    </form>
@endsection