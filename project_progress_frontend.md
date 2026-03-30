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

## ✅ Sesión 23/03/2026 — Carrito con localStorage

### Pieza 1 — Drawer HTML (escrito por el alumno)

Panel lateral oculto fuera de pantalla que se desliza desde la derecha:

```
CERRADO:  translateX(100%)  →  fuera de pantalla
ABIERTO:  translateX(0)     →  visible
```

Elementos clave añadidos al `index.blade.php`:

| id | Elemento | Para qué sirve |
|---|---|---|
| `cart-overlay` | Div fondo oscuro | Se muestra al abrir, cierra el drawer al pulsarlo |
| `cart-drawer` | Panel lateral | Contenedor principal del carrito |
| `cart-close` | Botón X | Cierra el drawer |
| `cart-items` | Div vacío | JS inyecta aquí los productos |
| `cart-total` | Span | JS actualiza el precio total |

Técnica CSS del drawer:
```css
/* Por defecto: oculto fuera de pantalla */
transform: translateX(100%);
transition: transform 0.3s ease;

/* Al abrir: desliza a su posición */
transform: translateX(0);
```

---

### Pieza 2 — JavaScript del carrito (escrito por el alumno)

Ubicado en `@push('scripts')`. Funciones implementadas:

#### `getCart()` / `saveCart(cart)`
```javascript
// Lee el carrito de localStorage (o [] si está vacío)
JSON.parse(localStorage.getItem('funko_cart') || '[]')

// Guarda el array como texto JSON
localStorage.setItem('funko_cart', JSON.stringify(cart))
```

#### `updateCartCount()`
- Usa `reduce()` para sumar todas las `quantity` del carrito
- Actualiza el texto de `#cart-count` en la navbar

#### `addToCart(btn)`
- Lee `data-id`, `data-name`, `data-price`, `data-image` del botón pulsado
- Usa `find()` para detectar si el producto ya existe → si existe, incrementa `quantity`; si no, hace `push()`
- Llama a `saveCart` → `updateCartCount` → `renderCart` → `showToast`

#### `removeFromCart(id)`
- Usa `filter()` para devolver un nuevo array sin el producto eliminado
- Llama a `saveCart` → `updateCartCount` → `renderCart`

#### `renderCart()`
- Si el carrito está vacío → muestra mensaje "Tu carrito está vacío"
- Si tiene items → construye HTML con template literals y lo inyecta en `#cart-items`
- Calcula el total acumulado y lo muestra en `#cart-total`

#### `openCart()` / `closeCart()`
- Cambia el `style.transform` del drawer entre `translateX(0)` y `translateX(100%)`
- Muestra/oculta el overlay con `classList.remove/add('hidden')`

#### `showToast(name)`
- Crea un `div` dinámicamente con `document.createElement`
- Lo añade al `body` → se elimina automáticamente a los 2.5 segundos con `setTimeout`

#### Eventos registrados
```javascript
cart-btn     → click → openCart()
cart-close   → click → closeCart()
cart-overlay → click → closeCart()
.add-to-cart → click → addToCart(btn)
// Al cargar la página:
updateCartCount() // restaura el contador desde localStorage
```

---



---

## ✅ Sesión 24/03/2026 — Footer + Navbar responsive (hamburguesa)

### Pieza 1 — Footer 3 columnas

Añadido al final de `shop/index.blade.php`, antes de `@endsection`.

**Estructura:**
```
┌──────────────────────────────────────────────────────┐
│  ⭐ FunkoShop          Tienda         Información     │
│  Descripción...        Catálogo       Envíos España   │
│                        Ver productos  Ed. limitadas   │
├──────────────────────────────────────────────────────┤
│  © 2026 FunkoShop. Todos los derechos reservados.    │
└──────────────────────────────────────────────────────┘
```

- `grid-cols-1 md:grid-cols-3 gap-10` → 3 columnas en desktop, 1 en mobile
- `{{ date('Y') }}` → año dinámico (no hardcodeado), para que se actualice el año solo
- `bg-slate-900 border-t border-slate-800` → consistente con el navbar
- `mt-0` → pegado a la sección del catálogo sin espacio

---

### Pieza 2 — Navbar responsive (menú hamburguesa)

**Elementos añadidos:**

| id / clase | Elemento | Para qué sirve |
|---|---|---|
| `#menu-btn` | Botón hamburguesa ☰ | Solo visible en mobile (`md:hidden`), abre el menú |
| `#mobile-menu` | Div menú desplegable | Oculto por defecto (`hidden md:hidden`) |
| `#search-input-mobile` | Input buscador mobile | Sincronizado con `#search-input` del desktop |

**Posición del botón hamburguesa:** a la **derecha del carrito** → orden final en mobile: `Logo | 🛒 ☰`

**JavaScript añadido en `@push('scripts')`:**
```javascript
// Toggle del menú mobile
menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// Sincronización buscador mobile → desktop
searchInputMobile.addEventListener('input', function () {
    searchInput.value = this.value;
    applyFilters();
});
```

**Concepto clave — `classList.toggle('hidden')`:**
Si el elemento tiene la clase `hidden` → la quita (muestra). Si no la tiene → la añade (oculta). Un solo método para abrir y cerrar.

---

## ⏳ Lo que queda por hacer

### 🛒 Carrito con localStorage
- [x] Panel lateral deslizante (drawer)
- [x] `addToCart`, `removeFromCart`, `renderCart`, `updateCartCount`
- [x] Toast de confirmación al añadir producto
- [x] Subtotal calculado dinámicamente
- [x] Persistencia con localStorage

### 🎨 Mejoras visuales
- [x] Footer de la tienda
- [x] Menú hamburguesa en mobile (navbar responsive)
- [x] Checkout completo: modal → POST → DB → confirmación + vaciar carrito — 27/03
- [x] Botón "Agotado" deshabilitado en cards si `stock = 0` — 30/03
- [ ] Modal o página de detalle del funko (opcional)

---

## 🗺️ Ruta de trabajo recomendada (actualizada 30/03/2026)

1. ✅ **Navbar + Hero + Filtros + Buscador** — 20/03
2. ✅ **Carrito con localStorage** — 23/03
3. ✅ **Footer** de la tienda — 24/03
4. ✅ **Navbar responsive** → menú hamburguesa mobile — 24/03
5. ✅ **Backend**: campo `stock` en `funkos` + tabla `orders` — 24/03
6. ✅ **Checkout completo**: modal → POST → DB → confirmación + vaciar carrito — 27/03
7. ✅ **Botón "Agotado"** en cards si `stock = 0` — 30/03
8. ⏳ **Login/registro opcional** en la tienda (`@guest` / `@auth` en navbar)

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
| `localStorage` → persistencia del carrito sin backend | Cart JS |
| `document.createElement` + `setTimeout` → toast dinámico | `showToast()` |
| `classList.toggle('hidden')` → abrir/cerrar menú con un método | Hamburger JS |
| `grid-cols-1 md:grid-cols-3` → footer responsive | Footer |
| `{{ date('Y') }}` → año dinámico en Blade | Footer copyright |
