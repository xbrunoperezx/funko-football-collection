@extends('layouts.shop')

@section('content')

{{-- ============================================================
     NAVBAR — sticky + efecto glass
     - sticky top-0: se queda fija al hacer scroll
     - backdrop-blur-md: desenfoca el contenido detrás
     - bg-slate-900/80: fondo oscuro semitransparente (80% opacidad)
     - z-50: siempre por encima del resto del contenido
     ============================================================ --}}
<nav class="sticky top-0 z-50 bg-slate-900 border-b border-slate-700" style="backdrop-filter: blur(12px); background-color: rgba(15,23,42,0.85);">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

        {{-- IZQUIERDA: Logo + nombre de la tienda --}}
        <a href="{{ route('shop') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg group-hover:bg-amber-400 transition-colors duration-200">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white tracking-tight group-hover:text-amber-400 transition-colors duration-200">
                FunkoShop
            </span>
        </a>

        {{-- CENTRO: Buscador en tiempo real --}}
        {{-- El id="search-input" lo usará JavaScript para filtrar las cards --}}
        <div class="hidden md:flex items-center flex-1 max-w-md mx-8">
            <div class="relative w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    id="search-input"
                    type="text"
                    placeholder="Buscar funko..."
                    class="w-full bg-white/10 border border-white/20 rounded-lg pl-10 pr-4 py-2 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition duration-200"
                >
            </div>
        </div>

        {{-- DERECHA: Enlace catálogo + botón carrito + hamburgesa --}}
        <div class="flex items-center gap-6">
            <a href="{{ route('shop') }}" class="hidden md:block text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">
                Catálogo
            </a>

            {{-- Botón carrito con contador dinámico --}}
            {{-- id="cart-count": JavaScript actualizará este número cuando se añada un producto --}}
            <button
                id="cart-btn"
                class="relative flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-amber-500/30 active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 009 18h6a2 2 0 001.95-1.55L21 9H5.4"/>
                </svg>
                <span>Carrito</span>
                {{-- Este span se actualiza dinámicamente desde JS --}}
                <span id="cart-count" class="bg-white text-amber-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    0
                </span>
            </button>

            {{-- Botón hamburguesa — solo visible en mobile (md:hidden) --}}
            {{-- id="menu-btn": JS lo usará para abrir/cerrar el menú --}}
            <button id="menu-btn" class="md:hidden text-slate-300 hover:text-white transition-colors duration-200 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- MENU MOBILE - oculto por defecto, se muesrta la pulsar ☰ --}}
    {{-- id="mobile-menu": JS añade/quita la clase 'hiden' --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-slate-700 px-6 py-4 space-y-3">

        {{-- Buscador mobile --}}
        {{-- id="search-input-mobile": sincronizaremos con el buscador principal --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input
                id="search-input-mobile"
                type="text"
                placeholder="Buscar funko..."
                class="w-full bg-white/10 border border-white/20 rounded-lg pl-10 pr-4 py-2 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition duration-200"
            >
        </div>

        {{-- Enlace catálogo mobile --}}
        <a href="{{ route('shop') }}" class="block text-sm font-medium text-slate-300 hover:text-amber-400 transition-colors duration-200 py-1">
            Catálogo
        </a>

    </div>
</nav>




{{-- ============================================================
     HERO — sección de bienvenida con imagen del estadio
     - relative + h-[500px]: altura fija para el hero
     - overflow-hidden: recorta la imagen al contenedor
     - El contenido (texto) usa relative z-10 para estar encima del overlay
     ============================================================ --}}
<section class="relative h-[500px] overflow-hidden flex items-center">

    {{-- Imagen de fondo del estadio --}}
    <img
        src="{{ asset('images/funkos/estadio.png') }}"
        alt="Estadio"
        class="absolute inset-0 w-full h-full object-cover scale-105"
    >

    {{-- Overlay oscuro en degradé para que el texto sea legible --}}
    {{-- from-slate-950: negro profundo en la izquierda --}}
    {{-- to-slate-900/60: más transparente hacia la derecha --}}
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-slate-900/40"></div>

    {{-- Contenido del hero: texto + estadísticas --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
        <div class="max-w-2xl">

            {{-- Badge superior --}}
            <span class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-500/40 text-amber-400 text-xs font-semibold px-3 py-1 rounded-full mb-6 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                Colección Oficial
            </span>

            {{-- Título principal — jerarquía visual con dos tamaños --}}
            <h1 class="text-5xl md:text-6xl font-black text-white leading-tight mb-4 tracking-tight">
                Funkos de
                <span class="text-amber-400">Fútbol</span>
            </h1>

            {{-- Subtítulo descriptivo --}}
            <p class="text-slate-300 text-lg mb-8 leading-relaxed">
                Descubre la colección más completa de figuras Funko Pop de tus jugadores favoritos.
            </p>

            {{-- Estadísticas dinámicas desde PHP --}}
            {{-- $funkos->count() = modelos distintos --}}
            {{-- $funkos->sum('stock') = unidades totales en stock --}}
            {{-- $categories->count() viene del controlador --}}
            <div class="flex items-center gap-8">
                <div>
                    <p class="text-3xl font-black text-white">{{ $funkos->count() }}</p>
                    <p class="text-slate-400 text-sm">Modelos de funko</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div>
                    <p class="text-3xl font-black text-white">{{ $funkos->sum('stock') }}</p>
                    <p class="text-slate-400 text-sm">Unidades en stock</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div>
                    <p class="text-3xl font-black text-white">{{ $categories->count() }}</p>
                    <p class="text-slate-400 text-sm">Categorías</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Flecha de scroll hacia el catálogo --}}
    {{-- href="#catalogo": enlaza con el id de la sección del grid --}}
    <a href="#catalogo" class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 hover:text-amber-400 transition-colors duration-200 animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </a>
</section>


{{-- ============================================================
     CATÁLOGO — filtros + grid de productos
     - id="catalogo": destino del enlace de la flecha del hero
     - bg-gray-950: fondo oscuro consistente con el layout
     ============================================================ --}}
<section id="catalogo" class="bg-[#030712] min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Título de sección + contador de resultados --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Catálogo completo</h2>
                {{-- id="results-count": JS actualizará este número al filtrar --}}
                <p class="text-slate-400 text-sm mt-1"><span id="results-count">{{ $funkos->count() }}</span> productos encontrados</p>
            </div>
        </div>

        {{-- ================================================
             FILTROS DE CATEGORÍA
             - data-filter="all": muestra todos los funkos
             - data-filter="{id}": muestra solo los de esa categoría
             - active-filter: clase CSS para el botón seleccionado
             ================================================ --}}
        <div id="filter-buttons" class="flex flex-wrap gap-2 mb-10">

            {{-- Botón "Todos" — activo por defecto --}}
            <button
                class="filter-btn active-filter px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 focus:outline-none"
                data-filter="all"
            >
                Todos
            </button>

            {{-- Un botón por cada categoría de la base de datos --}}
            @foreach($categories as $category)
                <button
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 focus:outline-none"
                    data-filter="{{ $category->id }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach

        </div>

        {{-- Grid de productos --}}
        <div id="funkos-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($funkos as $funko)
                {{-- data-name y data-category: JS los usa para filtrar y buscar --}}
                <div
                    class="funko-card bg-slate-800/60 border border-slate-700/50 rounded-2xl overflow-hidden flex flex-col transition-all duration-300 hover:border-amber-500/50 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-500/10 group"
                    data-name="{{ strtolower($funko->name) }}"
                    data-category="{{ $funko->category_id }}"
                >
                    {{-- Imagen del funko --}}
                    <div class="relative bg-gradient-to-br from-slate-700 to-slate-800 h-52 flex items-center justify-center overflow-hidden">
                        <img
                            src="{{ asset($funko->image_path ?? 'images/funkos/default.png') }}"
                            alt="{{ $funko->name }}"
                            class="h-44 w-auto object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-110"
                        >
                        {{-- Badge de categoría sobre la imagen --}}
                        <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-sm text-amber-400 text-xs font-medium px-2 py-1 rounded-md border border-amber-500/20">
                            {{ $funko->category->name ?? 'Sin categoría' }}
                        </span>
                    </div>

                    {{-- Info del producto --}}
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-white font-semibold text-base mb-1 leading-tight">{{ $funko->name }}</h3>
                        <p class="text-slate-400 text-xs mb-4">{{ $funko->era ?? '' }}</p>

                        {{-- Precio + botón en la misma fila --}}
                        <div class="mt-auto flex items-center justify-between gap-3">
                            <span class="text-amber-400 font-black text-xl">${{ number_format($funko->price, 2) }}</span>
                            {{-- Botón añadir al carrito --}}
                            {{-- data-id, data-name, data-price: JS los leerá para guardar en localStorage --}}
                            <button
                                class="add-to-cart flex-1 bg-amber-500 hover:bg-amber-400 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                                data-id="{{ $funko->id }}"
                                data-name="{{ $funko->name }}"
                                data-price="{{ $funko->price }}"
                                data-image="{{ asset($funko->image_path ?? 'images/funkos/default.png') }}"
                            >
                                + Añadir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mensaje cuando no hay resultados de búsqueda --}}
        <div id="no-results" class="hidden text-center py-20">
            <p class="text-slate-500 text-lg">No se encontraron funkos con ese criterio.</p>
        </div>

    </div>
</section>
{{-- ============================================================
     CRACCION--DRAWER (VENTANA LATERLA COULTA DEL CARRITO)
============================================================ --}}
{{-- OVERLAY: fondo oscuro detrás del drawer --}}
{{-- Al pulsar encima, se cierra el carrito --}}
<div id="cart-overlay"
     class="fixed inset-0 bg-black/60 z-40 hidden"
     style="backdrop-filter: blur(2px);">
</div>

{{-- DRAWER: panel lateral del carrito --}}
<div id="cart-drawer" 
    class = "fixed top-0 right-0 h-full w-96 bg-slate-900 z-50 flex flex-col shadow-2xl border-l border-slate-700" 
    style="transform: translateX(100%); transition: transform 0.3s ease;">

    {{-- Cabecera del Drawer --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-700">
        <h2 class="text-white font-bold text-lg">Tu carrito</h2>

        {{-- Botón cerrar --}}
        <button id="cart-close" class="text-slate-400 hover:text-white transition">
            {{-- Icono X --}}
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>     
        </button>
    </div>

    {{-- Lista de productos (JS la llenara aqui) --}}
    <div id="cart-items" class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
        {{-- JS inyectara los items aqui --}}
    </div>

    {{-- Pie del drawer: subtotal + boton --}}
    <div class="px-6 py-5 border-t border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-400 text-sm">Total</span>
            <span id="cart-total" class="text-white font-black text-xl">$0.00</span>
        </div>
        <button
        id="checkout-btn" 
        class="w-full bg-amber-500 hover:bg-amber-400 text-white font-semibold py-3 rounded-xl transition-all duration-200 active:scale-95">
        Finalizar compra
        </button>
    </div>
</div>


{{-- ============================================================
     MODAL CHECKOUT — formulario de datos del comprador
     - fixed inset-0: cubre toda la pantalla
     - z-[60]: por encima del drawer (z-50)
     - hidden por defecto, JS lo muestra al pulsar "Finalizar compra"
     ============================================================ --}}
<div id="checkout-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4"
     style="background-color: rgba(0,0,0,0.75); backdrop-filter: blur(4px);">

    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-md p-8 relative">

        {{-- Botón cerrar --}}
        <button id="checkout-modal-close" class="absolute top-4 right-4 text-slate-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <h2 class="text-white font-bold text-xl mb-6">Datos de envío</h2>

        {{-- Formulario que hace POST a /checkout --}}
        <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
            @csrf

            {{-- Campo oculto donde JS meterá el carrito como JSON --}}
            <input type="hidden" name="cart" id="cart-input">

            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm mb-1">Nombre completo</label>
                    <input type="text" name="name" required
                           class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-1">Email</label>
                    <input type="email" name="email" required
                           class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-1">Dirección de envío</label>
                    <textarea name="address" required rows="3"
                              class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none"></textarea>
                </div>
            </div>

            <button type="submit"
                    class="w-full mt-6 bg-amber-500 hover:bg-amber-400 text-white font-semibold py-3 rounded-xl transition-all duration-200 active:scale-95">
                Confirmar pedido
            </button>
        </form>

    </div>
</div>



{{-- ============================================================
     FOOTER
     - border-t: línea separadora con la sección anterior
     - grid md:grid-cols-3: 3 columnas en desktop, 1 en mobile
     ============================================================ --}}
<footer class="bg-slate-900 border-t border-slate-800 mt-0">

    {{-- Cuerpo del footer: 3 columnas --}}
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">

        {{-- Columna 1: Marca y descripción --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg">FunkoShop</span>
            </div>
            <p class="text-slate-400 text-sm leading-relaxed">
                La colección más completa de figuras Funko Pop de fútbol. Leyendas del deporte en tu estantería.
            </p>
        </div>

        {{-- Columna 2: Navegación --}}
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Tienda</h4>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('shop') }}" class="text-slate-400 text-sm hover:text-amber-400 transition-colors duration-200">
                        Catálogo completo
                    </a>
                </li>
                <li>
                    <a href="{{ route('shop') }}#catalogo" class="text-slate-400 text-sm hover:text-amber-400 transition-colors duration-200">
                        Ver productos
                    </a>
                </li>
            </ul>
        </div>

        {{-- Columna 3: Información --}}
        <div>
            <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Información</h4>
            <ul class="space-y-2">
                <li>
                    <span class="text-slate-400 text-sm">Envíos a toda España</span>
                </li>
                <li>
                    <span class="text-slate-400 text-sm">Colecciones oficiales</span>
                </li>
                <li>
                    <span class="text-slate-400 text-sm">Ediciones limitadas</span>
                </li>
            </ul>
        </div>

    </div>

    {{-- Barra inferior: copyright --}}
    <div class="border-t border-slate-800 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <p class="text-slate-500 text-xs">
                © {{ date('Y') }} FunkoShop. Todos los derechos reservados.
            </p>
            <p class="text-slate-600 text-xs">
                Hecho con Laravel + Tailwind
            </p>
        </div>
    </div>

</footer>




{{-- ============================================================
     ESTILOS CSS personalizados
     ============================================================ --}}
<style>
    /* Animación fade-in para las cards al cargar */
    @keyframes fade-in-up {
        0%   { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .funko-card {
        animation: fade-in-up 0.5s ease forwards;
    }
    /* Escalonamos la animación de cada card */
    .funko-card:nth-child(1)  { animation-delay: 0.05s; }
    .funko-card:nth-child(2)  { animation-delay: 0.10s; }
    .funko-card:nth-child(3)  { animation-delay: 0.15s; }
    .funko-card:nth-child(4)  { animation-delay: 0.20s; }
    .funko-card:nth-child(5)  { animation-delay: 0.25s; }
    .funko-card:nth-child(6)  { animation-delay: 0.30s; }
    .funko-card:nth-child(7)  { animation-delay: 0.35s; }
    .funko-card:nth-child(8)  { animation-delay: 0.40s; }

    /* Estilos del botón de filtro activo */
    .filter-btn {
        background-color: rgba(51, 65, 85, 0.6); /* slate-700/60 */
        color: #94a3b8; /* slate-400 */
        border: 1px solid rgba(71, 85, 105, 0.5);
    }
    .filter-btn:hover {
        background-color: rgba(71, 85, 105, 0.8);
        color: #fff;
    }
    /* Botón activo — color ámbar */
    .filter-btn.active-filter {
        background-color: #f59e0b; /* amber-500 */
        color: #fff;
        border-color: #f59e0b;
    }
</style>

@endsection


{{-- ============================================================
     JAVASCRIPT — Filtros por categoría + buscador en tiempo real
     Usamos @push('scripts') para inyectarlo en @stack('scripts')
     del layout, justo antes de cerrar el </body>
     ============================================================ --}}
@push('scripts')
<script>
    // Esperamos a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function () {

        // ── Referencias a los elementos del DOM ──────────────────
        const searchInput   = document.getElementById('search-input');   // buscador navbar
        const filterBtns    = document.querySelectorAll('.filter-btn');   // botones de categoría
        const funkoCards    = document.querySelectorAll('.funko-card');   // todas las cards
        const noResults     = document.getElementById('no-results');      // mensaje vacío
        const resultsCount  = document.getElementById('results-count');   // contador texto

        // Estado actual del filtro activo ("all" por defecto)
        let activeFilter = 'all';

        // ── Función principal de filtrado ─────────────────────────
        // Combina búsqueda por nombre Y filtro por categoría
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visible = 0;

            funkoCards.forEach(function (card) {
                const cardName     = card.dataset.name;      // ej: "beckenbauer"
                const cardCategory = card.dataset.category;  // ej: "2"

                // ¿Coincide con la búsqueda de texto?
                const matchesSearch = cardName.includes(searchTerm);

                // ¿Coincide con el filtro de categoría?
                // Si el filtro es "all" → siempre true
                const matchesFilter = (activeFilter === 'all') || (cardCategory === activeFilter);

                // Mostrar u ocultar según ambas condiciones
                if (matchesSearch && matchesFilter) {
                    card.style.display = '';   // muestra la card
                    visible++;
                } else {
                    card.style.display = 'none'; // oculta la card
                }
            });

            // Actualizar el contador de resultados visibles
            resultsCount.textContent = visible;

            // Mostrar el mensaje "sin resultados" si no hay nada
            noResults.classList.toggle('hidden', visible > 0);
        }

        // ── Evento: escribir en el buscador ───────────────────────
        searchInput.addEventListener('input', applyFilters);

        // ── Evento: pulsar un botón de categoría ─────────────────
        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {

                // Quitar la clase activa de todos los botones
                filterBtns.forEach(function (b) {
                    b.classList.remove('active-filter');
                });

                // Poner la clase activa en el botón pulsado
                btn.classList.add('active-filter');

                // Guardar el filtro activo ("all" o el id de categoría)
                activeFilter = btn.dataset.filter;

                // Aplicar los filtros combinados
                applyFilters();
            });
        });

    }); // fin DOMContentLoaded

    // ============================================================
    // CARRITO — localStorage
    // ============================================================

    //***1. Leer y guardar el carrito ─────────────────────────────

    // Obtiene el carrito desde localStorage
    // Si no existe todavía, devuelve un array vacío []
    function getCart() {
        return JSON.parse(localStorage.getItem('funko_cart') || '[]');
    }
    
    //Guardar el array del carrito en localStorage com otexto JSON
    function saveCart(cart) {
        localStorage.setItem('funko_cart', JSON.stringify(cart));
    }

    // 2. Actualizar el contador del carrito de la navbar el numero de cosas añadidas -----------------

    //suma las cantidades de todos los items y actualiza el #cart-count contador de carrito
    function updateCartCount() {
        const cart = getCart();
        // reduce()-> recorre el array sumando las cantidades
        const total = cart.reduce(function(sum,item) {
            return sum + item.quantity;
        }, 0); //el 0 es el valor inicial
        document.getElementById('cart-count').textContent = total;
    }

    // 3. Añadir porducto al carrito-------------------

    //lee los data-* del boton pulsado y añade el producto
    function addToCart(btn) {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = parseFloat(btn.dataset.price); //string -> numero
        const image = btn.dataset.image;

        let cart = getCart();

        // comprobar si ya existe el producto en el carrito
        const existing = cart.find(function(item) {
            return item.id === id;
        });

        if (existing) {
            //si ya existe -> incrementamos la cantidad
            existing.quantity += 1;
        } else {
            //si no existe -> añadimos con quantity: 1
            cart.push({ id, name, price, image, quantity: 1});
        }

        //llamamos a las funciones:
        saveCart(cart);   //guardar en localStorage
        updateCartCount();  //actualizar el numero en el navbar del carrito
        renderCart();   //actualiza el contenido del drawer (FALTA INPLEMENTAR LA FUNCION)
        showToast(name);   //mostrar notificacion(FALTA INPLEMENTAR)

    }

    // 4. Eliminar productos del carrito-------------------

    function removeFromCart(id) {
        //filter() -> devuelve u nnuevo array sin el item con ese id eliminado
        let cart = getCart().filter(function(item) {
            return item.id !== id;
        });
        saveCart(cart);   //guardar en localStorage
        updateCartCount();  //actualizar el numero en el navbar del carrito
        renderCart();   //actualiza el contenido del drawer (FALTA INPLEMENTAR LA FUNCION)
    }

     // 5. Pintar los items en el drawer (ventana oculta del carrito)

    function renderCart() {
        const cart = getCart();
        const container = document.getElementById('cart-items');
        const totalEl = document.getElementById('cart-total');

        //si el carrito esta vacio -> mostraremos el mensaje
        if(cart.length ===0) {
            container.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-center py-16">
                <p class="text-slate-500 text-sm mt-3">Tu carrito está vacío</p>
            </div>`;
            totalEl.textContent = '$0.00';
            return;
        }

        //construimos ahroa el HTML de cada item
        let html = '';
        let total = 0;

        cart.forEach(function(item) {
            total += item.price * item.quantity;
            html += `
            <div class="flex items-center gap-3 bg-slate-800 rounded-xl p-3">
                <img src="${item.image}" class="w-12 h-14 object-contain rounded-lg bg-slate-700 p-1">
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">${item.name}</p>
                    <p class="text-amber-400 text-sm font-bold">$${(item.price * item.quantity).toFixed(2)}</p>
                    <p class="text-slate-500 text-xs">Cantidad: ${item.quantity}</p>
                </div>
                <button onclick="removeFromCart('${item.id}')"
                        class="text-slate-500 hover:text-red-400 transition p-1 rounded-lg hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>`;
        });
       
        container.innerHTML = html;
        totalEl.textContent = '$' + total.toFixed(2);

    }

     // 6. Abrir y cerrar el drawer ventana oculta-------------------

    function openCart() {
        document.getElementById('cart-drawer').style.transform = 'translateX(0)';
        document.getElementById('cart-overlay').classList.remove('hidden');
        renderCart(); // refrescar contenido al abrir
    }

    function closeCart() {
        document.getElementById('cart-drawer').style.transform = 'translateX(100%)';
        document.getElementById('cart-overlay').classList.add('hidden');
    }

     // 7. toast de confirmacion----------------

    function showToast(name) {
        //crear el elemento toas dinamicamente
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-6 right-6 z-[100] bg-slate-800 border border-amber-500/40 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-300';
        toast.innerHTML = `
            <span class="text-amber-400">✓</span>
            <span class="text-sm"><strong>${name}</strong> añadido al carrito</span>`;

        document.body.appendChild(toast);

        // Eliminar el toast después de 2.5 segundos
        setTimeout(function() { toast.remove(); }, 2500);
    }


     // 8. Eventos.------------------------------------------------

     //Abrir carrito al pulsar el boton  en navbar
     document.getElementById('cart-btn').addEventListener('click', openCart)

     // Cerrar al pulsar la X del drawer
     document.getElementById('cart-close').addEventListener('click', closeCart);

     // Cerrar al pulsar el overlay de fondo
     document.getElementById('cart-overlay').addEventListener('click', closeCart);

     // Añadir al carrito al pulsar "+ Añadir" en cualquier card
     document.querySelectorAll('.add-to-cart').forEach(function(btn) {
        btn.addEventListener('click', function() { addToCart(btn); });
     });

     // Al cargar la página → restaurar el contador desde localStorage
     updateCartCount();



     // ── Menú mobile: botón hamburguesa ───────────────────────────
    // toggle('hidden') → si tiene la clase 'hidden' la quita, si no la añade
    const menuBtn    = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    // Buscador mobile sincronizado con el buscador de desktop
    // Al escribir en mobile → también aplica los filtros
    const searchInputMobile = document.getElementById('search-input-mobile');
    searchInputMobile.addEventListener('input', function() {
        // Copiamos el valor al input principal y lanzamos el filtro
        searchInput.value = searchInputMobile.value;
        applyFilters();
    });

    // ── Modal de checkout ─────────────────────────────────────────
    const checkoutBtn        = document.getElementById('checkout-btn');
    const checkoutModal      = document.getElementById('checkout-modal');
    const checkoutModalClose = document.getElementById('checkout-modal-close');
    const checkoutForm       = document.getElementById('checkout-form');
    const cartInput          = document.getElementById('cart-input');

    // Abrir el modal al pulsar "Finalizar compra"
    checkoutBtn.addEventListener('click', function () {
        const cart = getCart();

        // No permitir abrir el modal si el carrito está vacío
        if (cart.length === 0) {
            showToast('Añade productos antes de finalizar');
            return;
        }

        // Meter el carrito como JSON en el campo oculto del formulario
        cartInput.value = JSON.stringify(cart);

        // Mostrar el modal
        checkoutModal.classList.remove('hidden');
    });

    // Cerrar el modal con el botón X
    checkoutModalClose.addEventListener('click', function () {
        checkoutModal.classList.add('hidden');
    });

    // Cerrar el modal al pulsar fuera del recuadro
    checkoutModal.addEventListener('click', function (e) {
        if (e.target === checkoutModal) {
            checkoutModal.classList.add('hidden');
        }
    });
</script>
@endpush
