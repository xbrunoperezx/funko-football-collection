<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Pedidos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-muted fw-bold">#{{ $order->id }}</td>
                            <td class="fw-semibold">{{ $order->name }}</td>
                            <td class="text-muted">{{ $order->email }}</td>
                            <td class="text-end fw-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="text-center">
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($order->status === 'paid')
                                    <span class="badge bg-primary">Pagado</span>
                                @else
                                    <span class="badge bg-success">Enviado</span>
                                @endif
                            </td>
                            <td class="text-center text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('orders.update', $order->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                        <option value="pending"  {{ $order->status === 'pending'  ? 'selected' : '' }}>Pendiente</option>
                                        <option value="paid"     {{ $order->status === 'paid'     ? 'selected' : '' }}>Pagado</option>
                                        <option value="shipped"  {{ $order->status === 'shipped'  ? 'selected' : '' }}>Enviado</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay pedidos todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>