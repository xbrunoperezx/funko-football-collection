<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Funkos
        </h2>
    </x-slot>
    <div class="py-6">
        <a href="{{ route('funkos.create') }}" class="btn btn-success mb-3">Crear Nuevo Funko</a>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Categoría</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($funkos as $funko)
                <tr>
                    <td class="text-center">{{ $funko->id }}</td>
                    <td>
                        {{ $funko->name }}<br>
                        @if($funko->image_path)
                            <img src="{{ asset($funko->image_path) }}" alt="{{ $funko->name }}" width="60" class="mt-2">
                        @else
                            <span class="text-muted">Sin imagen</span>
                        @endif
                    </td>
                    <td>{{ $funko->category->name }}</td>
                    <td class="text-end">${{ number_format($funko->price, 2) }}</td>
                    <td class="text-center">
                        @if($funko->stock > 0)
                            {{ $funko->stock }}
                        @else
                            <span class="badge bg-danger">Sin stock</span>
                        @endif

                    </td>
                    <td class="text-center">
                        <a href="{{ route('funkos.edit', $funko->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <form action="{{ route('funkos.destroy', $funko->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event, this);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        function confirmDelete(event, form) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>