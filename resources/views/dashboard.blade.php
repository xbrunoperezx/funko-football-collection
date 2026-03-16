<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjeta Funkos -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center">
                    <span class="text-4xl font-bold text-blue-600">{{ $funkosCount }}</span>
                    <span class="mt-2 text-lg text-gray-700">Funkos</span>
                </div>
                <!-- Tarjeta Categorías -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center">
                    <span class="text-4xl font-bold text-green-600">{{ $categoriesCount }}</span>
                    <span class="mt-2 text-lg text-gray-700">Categorías</span>
                </div>
                <!-- Tarjeta Usuarios -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center">
                    <span class="text-4xl font-bold text-purple-600">{{ $usersCount }}</span>
                    <span class="mt-2 text-lg text-gray-700">Usuarios</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

