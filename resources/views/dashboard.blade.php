<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="row g-4">

                <!-- Tarjeta Funkos -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary fs-2">🧸</div>
                            <div>
                                <div class="fs-1 fw-bold text-primary">{{ $funkosCount }}</div>
                                <div class="text-muted">Funkos</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                            <a href="{{ route('funkos.index') }}" class="btn btn-sm btn-outline-primary w-100">Ver Funkos</a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Categorías -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success fs-2">🏷️</div>
                            <div>
                                <div class="fs-1 fw-bold text-success">{{ $categoriesCount }}</div>
                                <div class="text-muted">Categorías</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-success w-100">Ver Categorías</a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Usuarios -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="rounded-3 p-3 bg-purple bg-opacity-10 fs-2" style="background-color:#f3e8ff; color:#7c3aed;">👤</div>
                            <div>
                                <div class="fs-1 fw-bold" style="color:#7c3aed;">{{ $usersCount }}</div>
                                <div class="text-muted">Usuarios</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                            <a href="{{ route('users.index') }}" class="btn btn-sm w-100" style="border-color:#7c3aed; color:#7c3aed;">Ver Usuarios</a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Pedidos -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning fs-2">📦</div>
                            <div>
                                <div class="fs-1 fw-bold text-warning">{{ $ordersCount }}</div>
                                <div class="text-muted">Pedidos</div>
                                <div class="badge bg-warning text-dark mt-1">{{ $pendingCount }} pendientes</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4">
                            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-warning w-100">Ver Pedidos</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
