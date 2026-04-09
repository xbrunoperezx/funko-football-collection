# PROJECT_CONTEXT.md — Funko Football Collection
> Archivo maestro de contexto. Leer este archivo es suficiente para retomar el proyecto en cualquier sesión.
> Última actualización: 09/04/2026

---

## 🤖 ROL DE LA IA

Actúa siempre como **profesor y mentor** de programación.
- Explica primero el concepto, luego guía la implementación paso a paso
- Espera confirmación "OK" antes de continuar con más código
- No des soluciones completas sin explicación previa
- Añade comentarios explicativos en el código
- Corrige errores de razonamiento explicando el porqué
- Objetivo: **aprender mientras desarrollamos**, no solo terminar el código

---

## 🧱 STACK TECNOLÓGICO

| Capa | Tecnología |
|---|---|
| Backend | PHP · Laravel 11 · Eloquent ORM |
| Frontend tienda | Tailwind CSS v3 · JavaScript vanilla · Vite |
| Frontend admin | Bootstrap 5.3 · SweetAlert2 |
| Base de datos | MySQL (XAMPP) |
| Auth | Laravel Breeze |
| Entorno | XAMPP puerto 8080 · VS Code · Git |
| URL local | `http://localhost:8080` |

---

## 📁 ESTRUCTURA DE ARCHIVOS CLAVE

```
app/Http/Controllers/
  DashboardController.php   ← métricas del panel admin
  FunkoController.php       ← CRUD funkos + método shop()
  CategoryController.php    ← CRUD categorías
  UserController.php        ← CRUD usuarios
  OrderController.php       ← store(), thanks(), index(), update()

app/Http/Middleware/
  EnsureIsAdmin.php         ← comprueba role === 'admin', redirige a /shop si no

app/Models/
  Funko.php       ← fillable: name, era, image_path, description, price, stock, category_id
  Category.php    ← fillable: name, description
  User.php        ← fillable: name, email, password, role (enum: admin/user, default: user)
  Order.php       ← fillable: user_id(nullable), name, email, address, total, status

database/migrations/
  create_categories_table.php
  create_funkos_table.php
  add_stock_to_funkos_table.php   ← stock unsignedInteger default(0)
  create_orders_table.php         ← status default('pending')
  2026_02_16_110514_create_users_table.php ← incluye role enum('admin','user') default('user')

bootstrap/
  app.php   ← middleware alias registrado: 'admin' → EnsureIsAdmin::class

routes/
  web.php     ← dos grupos: middleware('auth') para perfil, middleware(['auth','admin']) para panel
  auth.php    ← rutas Breeze

resources/views/
  layouts/
    shop.blade.php        ← layout tienda pública (Tailwind + @stack('scripts'))
    app.blade.php         ← layout admin (Bootstrap 5.3 CSS + JS + SweetAlert2)
    navigation.blade.php  ← navbar admin Bootstrap dark
  dashboard.blade.php     ← 4 cards Bootstrap con métricas
  shop/
    index.blade.php       ← tienda completa: navbar, hero, filtros, grid, carrito, checkout
    thankyou.blade.php    ← confirmación de pedido + limpia localStorage
  funkos/
    index.blade.php       ← tabla Bootstrap + columna stock + badge "Sin stock"
    create.blade.php      ← formulario con campo stock
    edit.blade.php        ← formulario con campo stock
  categories/             ← CRUD completo
  users/                  ← CRUD completo
  orders/
    index.blade.php       ← tabla Bootstrap + badges estado + select cambio estado
```

---

## 🗄️ BASE DE DATOS

### Tabla `funkos`
| Campo | Tipo |
|---|---|
| id | autoincrement |
| name | string |
| era | string nullable |
| image_path | string nullable |
| description | text nullable |
| price | decimal(8,2) |
| stock | unsignedInteger default(0) |
| category_id | foreignId |

### Tabla `orders`
| Campo | Tipo | Nota |
|---|---|---|
| id | autoincrement | |
| user_id | foreignId nullable | null si compra sin login |
| name | string | nombre del comprador |
| email | string | |
| address | text | |
| total | decimal(8,2) | |
| status | string default('pending') | pending / paid / shipped |
| timestamps | — | |

### Relaciones Eloquent
```php
Funko     → belongsTo → Category
Category  → hasMany   → Funko
Order     → belongsTo → User (nullable)
```

---

## 🛣️ RUTAS (`routes/web.php`)

```php
// Pública
Route::get('/shop', [FunkoController::class, 'shop'])->name('shop');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/checkout/gracias/{order}', [OrderController::class, 'thanks'])->name('checkout.thanks');

// Cualquier usuario logueado (perfil)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Solo admins
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('funkos', FunkoController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'update']);
});
```

---

## ⚙️ CONTROLADORES — MÉTODOS CLAVE

### `OrderController`
```php
store(Request $request)   // valida → json_decode carrito → Order::create() → decrement stock → redirect thanks
thanks($order)            // muestra vista thankyou con $orderId
index()                   // Order::with('user')->latest()->get() → vista orders.index
update(Request $request, Order $order)  // valida 'in:pending,paid,shipped' → $order->update() → redirect
```

### `DashboardController`
```php
index()  // Funko::count(), Category::count(), User::count(), Order::count(), Order::where('status','pending')->count()
         // → view('dashboard', compact(...todos...))
```

### `FunkoController`
```php
shop()   // Funko::with('category')->get() + Category::all() → view('shop.index')
store()  // validate incluye 'stock' => 'required|integer|min:0'
update() // validate incluye stock, $updateData incluye 'stock'
```

---

## 🛍️ TIENDA PÚBLICA — FUNCIONALIDADES COMPLETAS

### Navbar
- Sticky + efecto glass (`backdrop-filter: blur`)
- Buscador en tiempo real (`id="search-input"`)
- Botón carrito con contador (`id="cart-count"`)
- `@guest` → enlace "Iniciar sesión" → `route('login')`
- `@guest` → "Iniciar sesión"
- `@auth` + `role === 'admin'` → nombre + "· Admin" con enlace a `route('dashboard')`
- `@auth` + `role === 'user'` → solo nombre + botón logout (form POST `route('logout')`)
- Menú hamburguesa mobile (`id="menu-btn"`, `id="mobile-menu"`)

### Hero
- Imagen `estadio.png` como fondo con overlay degradado
- Estadísticas dinámicas: `$funkos->count()`, `$funkos->sum('stock')`, `$categories->count()`

### Catálogo
- Filtros por categoría (`data-filter`) + buscador combinados en `applyFilters()`
- Cards con `data-name` y `data-category` para filtrado JS
- `@if($funko->stock > 0)` → botón ámbar "+ Añadir" / `@else` → botón gris `disabled` "Agotado"

### Carrito (localStorage)
- `getCart()` / `saveCart()` → `localStorage` con clave `funko_cart`
- `addToCart(btn)` → lee `data-id/name/price/image`, incrementa quantity si ya existe
- `removeFromCart(id)` → `filter()` sin ese id
- `renderCart()` → construye HTML en `#cart-items`, calcula total
- `openCart()` / `closeCart()` → `translateX(0/100%)`
- `showToast(name)` → `createElement` + `setTimeout(2500)`
- Drawer: `id="cart-drawer"` + overlay `id="cart-overlay"`

### Checkout
- Modal `id="checkout-modal"` (z-[60]) → se abre al pulsar "Finalizar compra"
- `<input type="hidden" name="cart" id="cart-input">` → JS inyecta JSON del carrito
- `POST /checkout` → `OrderController@store()`
- `thankyou.blade.php` → `localStorage.removeItem('funko_cart')` al cargar

---

## 🖥️ PANEL ADMIN — FUNCIONALIDADES COMPLETAS

### Layout (`layouts/app.blade.php`)
- Bootstrap 5.3 CSS + JS (bundle con Popper) via CDN
- SweetAlert2 via CDN
- Vite para Tailwind (coexisten sin conflicto)

### Navbar (`layouts/navigation.blade.php`)
- Bootstrap `navbar-dark bg-dark`
- Links activos con `request()->routeIs('seccion.*')`
- Dropdown usuario: "Mi perfil" + "Cerrar sesión"
- Links: Dashboard · Funkos · Categorías · Usuarios · Pedidos

### Dashboard
- 4 cards Bootstrap con iconos emoji, colores por sección
- Azul: Funkos | Verde: Categorías | Morado: Usuarios | Amarillo: Pedidos
- Cada card con botón "Ver X" → enlace directo a la sección
- Badge "X pendientes" en la card de Pedidos

### Funkos (`/funkos`)
- Tabla `table-striped table-bordered table-dark` en header
- Columna Stock: número si > 0 / badge `bg-danger` "Sin stock" si = 0
- SweetAlert2 para confirmar eliminación

### Orders (`/orders`)
- Tabla Bootstrap con badges: `bg-warning` Pendiente / `bg-primary` Pagado / `bg-success` Enviado
- `<select class="form-select form-select-sm" onchange="this.form.submit()">` → cambio instantáneo
- `@method('PUT')` + `@csrf`
- Flash `alert alert-success` al cambiar estado

---

## ✅ FASE 1 — COMPLETADA (07/04/2026)

| Fecha | Completado |
|---|---|
| Feb 2026 | CRUD Funkos + Categorías + Usuarios + Auth Breeze |
| 24/03 | Campo `stock` en funkos + tabla `orders` + modelo Order |
| 27/03 | OrderController completo + checkout + thankyou + stock en formularios |
| 30/03 | Admin orders panel + Bootstrap en admin + "Agotado" tienda + dashboard card pedidos |
| 02/04 | Rediseño visual admin: navbar Bootstrap dark + dashboard cards + orders Bootstrap |
| 06/04 | @guest/@auth en navbar tienda + enlace "· Admin" para usuarios autenticados |

## ✅ FASE 2 — EN PROGRESO

| Fecha | Completado |
|---|---|
| 09/04 | Sistema de roles: middleware EnsureIsAdmin + alias 'admin' + dos grupos de rutas + navbar condicional por rol + logout para usuarios |

---

## 🚀 FASE 2 — PENDIENTE (próximas sesiones)

### Prioridad 1 — Página de detalle del funko
- Ruta `GET /shop/{funko}` → `FunkoController@show()` público
- Vista `shop/show.blade.php`: galería imágenes, descripción larga, botón añadir carrito
- Botón "Ver detalles" en cada card de la tienda
- Posible: tabla `funko_images` para galería múltiple
- Posible: embed vídeo YouTube o `<video>` con autoplay muted
- Librería recomendada: **AOS** (Animate On Scroll) para animaciones de entrada

### ✅ Prioridad 2 — Sistema de roles (admin / cliente) — COMPLETADO 09/04
- Campo `role` en tabla `users` → enum('admin','user') default('user') ✅
- Middleware `EnsureIsAdmin` con nullsafe operator `?->role` ✅
- Alias `'admin'` registrado en `bootstrap/app.php` ✅
- Rutas separadas: `['auth','admin']` para panel, `'auth'` para perfil ✅
- Navbar tienda condicional por rol + logout para usuarios ✅
- Pendiente: historial de pedidos del cliente autenticado (`user_id` ya existe en `orders`)

### Prioridad 3 — Mejoras admin
- Paginación en listados (`->paginate(15)` + `{{ $items->links() }}`)
- Exportar pedidos a CSV
- Email automático al cliente al cambiar estado a `shipped`

### Prioridad 4 — Mejoras tienda
- Filtro por rango de precio (slider)
- Wishlist con localStorage
- Sección "Novedades" / "Funko destacado"
- Buscador con autocomplete

### Tecnologías recomendadas para Fase 2
- **Livewire** → componentes reactivos en Laravel sin API REST (mayor salto de calidad)
- **AOS** → animaciones al hacer scroll (2KB, sin dependencias)
- **Alpine.js** → ya incluido con Breeze, usar para tabs y acordeones

---

## 🧠 ERRORES FRECUENTES Y SOLUCIONES

| Error | Causa | Solución |
|---|---|---|
| Error 419 CSRF | Sesión caducada tras reiniciar XAMPP | `php artisan config:clear` + `cache:clear` + Ctrl+Shift+R |
| Clases Tailwind no se aplican | Falta recompilar | `npm run build` |
| `gray-950` no funciona | No existe en Tailwind < v3.3 | Usar `bg-[#030712]` |
| Badge Bootstrap sin color | Falta Bootstrap JS o CSS | Comprobar CDN en `layouts/app.blade.php` |
| Stock no se descuenta | `stock = 0` en DB, condición `0 >= qty` falla | Actualizar stock desde admin antes de probar |
| `<?php` en blade | Los .blade.php no llevan `<?php` | Eliminarlo |

---

## 🧩 CONCEPTOS CLAVE APRENDIDOS

| Concepto | Aplicación |
|---|---|
| `Route::resource()` | CRUD completo con 7 rutas automáticas |
| `middleware('auth')` | Proteger grupo de rutas |
| `Route Model Binding` | `Order $order` en parámetro del método |
| `compact()` | Pasar múltiples variables a la vista |
| `session('success')` | Mensajes flash entre redirecciones |
| `@stack` + `@push` | Inyectar JS específico desde vistas al layout |
| `data-*` attributes | Puente PHP/Blade → JavaScript |
| `localStorage` | Persistencia del carrito sin backend |
| `collect()->sum()` | Suma sobre colección PHP |
| `decrement('campo', n)` | Resta directa en DB |
| `json_decode($json, true)` | JSON string → array PHP |
| `@guest` / `@auth` | Contenido condicional según autenticación |
| `request()->routeIs('x.*')` | Detectar ruta activa en navbar |
| `foreignId()->nullable()->nullOnDelete()` | FK opcional |
| `unsignedInteger()->default(0)` | Campo numérico sin negativos |
| `@forelse / @empty` | Loop con mensaje si vacío |
| `@method('PUT')` | Spoofing de método HTTP en formularios Blade |
| `php artisan make:middleware` | Crear middleware personalizado |
| `?->` nullsafe operator | Evitar error si `user()` devuelve null |
| Early return en middleware | Salir antes si condición falla, sin `else` |
| `$middleware->alias()` en `bootstrap/app.php` | Registrar alias de middleware en Laravel 11 |
| `['auth', 'admin']` en Route::middleware | Aplicar múltiples middleware a un grupo |
| `@if(Auth::user()->role === 'admin')` anidado en `@guest` | Navbar condicional por rol |
| `<form method="POST" action="route('logout')">` | Logout seguro con POST en Blade |
