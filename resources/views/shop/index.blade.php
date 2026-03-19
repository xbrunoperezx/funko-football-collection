@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Catálogo de Funkos</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($funkos as $funko)
            <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                <img src="{{ asset($funko->image_path ?? 'images/funkos/default.png') }}" alt="{{ $funko->name }}" class="w-32 h-32 object-cover mb-4">
                <h2 class="text-xl font-semibold mb-2">{{ $funko->name }}</h2>
                <p class="text-gray-600 mb-1">Categoría: {{ $funko->category->name ?? 'Sin categoría' }}</p>
                <p class="text-green-600 font-bold mb-3">${{ number_format($funko->price, 2) }}</p>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Añadir al carrito</button>
            </div>
        @endforeach
    </div>
</div>
@endsection
