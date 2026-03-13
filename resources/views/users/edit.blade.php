@extends('layouts.app')

@section('content')
    <h1 class="text-center my-4">***Editar Usuario***</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="w-50 mx-auto">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" placeholder="Ingrese el nombre del usuario" required>
        </div>

        <div class="form-group mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Ingrese el correo electrónico" required>
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label">Contraseña (dejar en blanco para no cambiar):</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese una nueva contraseña">
        </div>

        <div class="form-group mb-3">
            <label for="role" class="form-label">Rol:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection