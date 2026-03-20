# Progreso Frontend — Tienda Pública (`/shop`)

> Este archivo documenta exclusivamente el desarrollo del frontend de la tienda pública.
> El progreso del backend y panel admin está en `project_progress.md`.

---

## 📁 Archivos relevantes

```
resources/
  views/
    layouts/
      shop.blade.php        ← layout exclusivo de la tienda pública
    shop/
      index.blade.php       ← vista principal: catálogo de funkos
  css/
    app.css                 ← @tailwind base/components/utilities
app/
  Http/Controllers/
    FunkoController.php     ← método shop() → pasa $funkos + $categories
  Models/
    Funko.php               ← name, era, image_path, price, category_id
    Category.php            ← name, description
routes/
  web.php                   ← Route::get('/shop') → pública, sin auth
```

---

## ✅ Sesión 20/03/2026 — Lo que se construyó

### Paso 1 — Layout: `layouts/shop.blade.php`

**Qué se añadió:**
- Google Fonts (Inter) → tipografía moderna y profesional
- Fondo oscuro `bg-[#030712]` en el `<body>` (nota: `gray-950` no existe en Tailwind < v3.3)
- Meta description para SEO básico
- `@stack('scripts')` al final del `<body>` → punto de inyección de JS desde las vistas

**Concepto clave — `@stack` + `@push`:**
```blade
{{-- En el layout --}}
@stack('scripts')

{{-- En cualquier vista --}}
@push('scripts')
    <script> /* JS específico de esta vista */ </script>
@endpush
```
Permite que cada vista inyecte su propio JS al final del body sin tocar el layout.

---

### Paso 2 — Controlador: `FunkoController@shop()`

**Antes:**
```php
public function shop() {
    $funkos = Funko::with('category')->get();
    return view('shop.index', compact('funkos'));
}
```

**Después:**
```php
public function shop() {
    $funkos     = Funko::with('category')->get();
    $categories = Category::all(); // ← añadido para los filtros
    return view('shop.index', compact('funkos', 'categories'));
}
```

**Por qué:** la vista necesita `$categories` para generar los botones de filtro dinámicamente desde la base de datos.

---

### Paso 3 — Vista principal: `shop/index.blade.php`

#### 3a. Navbar sticky (glass)

```
┌─────────────────────────────────────────────────────────────┐
│  ⭐ FunkoShop      [ 🔍 Buscar funko... ]    Catálogo  🛒 0 │
└─────────────────────────────────────────────────────────────┘
```

- `sticky top-0 z-50` → se queda fija al hacer scroll
- `backdrop-filter: blur(12px)` + fondo `rgba(15,23,42,0.85)` → efecto glass
- `id="search-input"` → JS filtra las cards al escribir
- `id="cart-count"` → JS actualiza el contador al añadir productos
- `id="cart-btn"` → JS abrirá el panel del carrito (pendiente)

#### 3b. Hero section

- Imagen `estadio.png` como fondo con `position: absolute`
- Overlay `bg-gradient-to-r from-slate-950` para legibilidad del texto
- Badge "Colección Oficial" con punto parpadeante (`animate-pulse`)
- Estadísticas dinámicas desde PHP: `{{ $funkos->count() }}` y `{{ $categories->count() }}`
- Flecha `animate-bounce` con `href="#catalogo"` → scroll al grid

#### 3c. Filtros de categoría

```
[ Todos ]  [ Segunda Edición ]  [ Clásicos ]  ← generados desde $categories
```

- Generados con Blade: `@foreach($categories as $category)`
- Cada botón: `data-filter="{{ $category->id }}"`
- Botón "Todos": `data-filter="all"` → activo por defecto
- Estilos CSS: `.filter-btn` (inactivo, slate) / `.active-filter` (activo, amber)

#### 3d. Grid de cards

- `grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6`
- Cada card tiene atributos `data-*` para que JS pueda filtrarlas:
  ```html
  <div class="funko-card"
       data-name="beckenbauer"
       data-category="2">
  ```
- Botón "Añadir al carrito" con atributos para el carrito (pendiente):
  ```html
  <button class="add-to-cart"
          data-id="1"
          data-name="Beckenbauer"
          data-price="19.99"
          data-image="images/funkos/...">
  ```
- Animación escalonada `fade-in-up` con `animation-delay` por card

#### 3e. JavaScript (filtros + buscador)

Ubicado en `@push('scripts')` al final de la vista.

```javascript
// Función centralizada que combina búsqueda + filtro de categoría
function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase();

    funkoCards.forEach(function (card) {
        const matchesSearch = card.dataset.name.includes(searchTerm);
        const matchesFilter = (activeFilter === 'all') || (card.dataset.category === activeFilter);

        card.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
    });
}

// Evento buscador → llama a applyFilters()
searchInput.addEventListener('input', applyFilters);

// Evento botones filtro → actualiza activeFilter y llama a applyFilters()
filterBtns.forEach(btn => btn.addEventListener('click', ...));
```

**Por qué una función centralizada:** tanto el buscador como los botones de categoría llaman a la misma función. Así ambos filtros se **combinan** sin duplicar lógica.

---

### Nota importante — Tailwind y Vite

Cada vez que se añaden **clases nuevas de Tailwind** a los blade files, hay que recompilar:

```bash
npm run build        # producción (una vez)
npm run dev          # desarrollo (watch automático)
```

Sin esto, el navegador usa el CSS anterior y las clases nuevas no se aplican.

---

## ⏳ Lo que queda por hacer

### 🛒 Carrito con localStorage — PRÓXIMA SESIÓN
- [ ] Panel lateral deslizante (drawer) que se abre al pulsar `#cart-btn`
- [ ] Lógica JS con `localStorage`:
  - `addToCart(id, name, price, image)` al pulsar `.add-to-cart`
  - `removeFromCart(id)` para eliminar un producto del carrito
  - `updateCartCount()` → actualiza `#cart-count` en la navbar
  - `renderCart()` → pinta los items dentro del drawer
- [ ] Toast/notificación al añadir un producto ("✓ Añadido al carrito")
- [ ] Subtotal calculado dinámicamente
- [ ] Persistencia: el carrito se mantiene al recargar la página (localStorage)

### 🎨 Mejoras visuales pendientes
- [ ] Footer de la tienda
- [ ] Menú hamburguesa en mobile (navbar responsive)
- [ ] Modal o página de detalle del funko (opcional)
- [ ] Checkout simulado (formulario de datos, sin pasarela real)

---

## 🗺️ Ruta de trabajo recomendada

1. **Carrito con localStorage** → drawer lateral + toast
2. **Footer** de la tienda
3. **Responsive mobile** → navbar hamburguesa
4. **Checkout simulado** → formulario de datos
5. **Dashboard admin** → estadísticas reales

---

## 🧠 Conceptos aprendidos

| Concepto | Dónde se aplicó |
|---|---|
| `@stack` + `@push` | Layout shop + index.blade.php |
| `compact('a', 'b')` → pasa múltiples variables a la vista | FunkoController@shop |
| `data-*` atributos → puente entre PHP/Blade y JavaScript | Cards + botones filtro |
| Función centralizada para combinar filtros | `applyFilters()` en JS |
| `gray-950` no existe en Tailwind < v3.3 → usar `bg-[#030712]` | Layout shop |
| Tailwind JIT requiere `npm run build` al añadir clases nuevas | Diagnóstico estilos rotos |
| `sticky top-0 z-50` + `backdrop-filter` → navbar glass | Navbar |
| `position: absolute` en imagen + overlay degradado → hero | Hero section |
