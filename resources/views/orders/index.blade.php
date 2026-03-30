<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedidos
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">#</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Cliente</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Total</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Estado</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Fecha</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-gray-500">#{{ $order->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $order->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->email }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">Pendiente</span>
                                    @elseif($order->status === 'paid')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full">Pagado</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Enviado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    {{-- Formulario para cambiar el estado --}}
                                    <form method="POST" action="{{ route('orders.update', $order->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()"
                                                class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="pending"  {{ $order->status === 'pending'  ? 'selected' : '' }}>Pendiente</option>
                                            <option value="paid"     {{ $order->status === 'paid'     ? 'selected' : '' }}>Pagado</option>
                                            <option value="shipped"  {{ $order->status === 'shipped'  ? 'selected' : '' }}>Enviado</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No hay pedidos todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>