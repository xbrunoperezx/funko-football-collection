# Progreso Backend — Panel Admin + Checkout (`/dashboard`, `/funkos`, `/categories`, `/users`, `/orders`)

> Este archivo documenta el desarrollo del backend, modelos, migraciones y panel administrativo.
> El progreso del frontend de la tienda pública está en `project_progress_frontend.md`.

---

## 📁 Archivos relevantes

```
app/
  Http/Controllers/
    DashboardController.php   ← estadísticas del panel
    FunkoController.php       ← CRUD funkos + método shop()
    CategoryController.php    ← CRUD categorías
    UserController.php        ← CRUD usuarios
    OrderController.php       ← store() + thanks() — checkout funcional
  Models/
    Funko.php                 ← name, era, image_path, price, stock, category_id
    Category.php              ← name, description
    User.php                  ← Laravel Breeze auth
    Order.php                 ← user_id (nullable), name, email, address, total, status
database/
  migrations/
    create_categories_table.php
    create_funkos_table.php
    add_stock_to_funkos_table.php   ← añadido 24/03
    create_orders_table.php         ← añadido 24/03
routes/
  web.php                     ← rutas CRUD protegidas + rutas checkout públicas
  auth.php                    ← rutas de Laravel Breeze
resources/views/
  layouts/
    app.blade.php             ← layout del panel admin
    navigation.blade.php      ← navbar del panel admin
  dashboard.blade.php         ← estadísticas (funkos, categorías, usuarios)
  funkos/
    index.blade.php           ← listado con SweetAlert2
    create.blade.php          ← formulario con campo stock ← actualizado 27/03
    edit.blade.php            ← formulario con campo stock ← actualizado 27/03
  categories/                 ← vistas CRUD
  users/                      ← vistas CRUD
  shop/
    thankyou.blade.php        ← página de confirmación de compra ← añadido 27/03
```

---

## ✅ Sesiones anteriores (Feb 2026) — Panel Admin completo

### Autenticación
- Laravel Breeze instalado → login, registro, logout, dashboard
- Rutas protegidas con `middleware(['auth', 'verified'])` y `middleware('auth')`

### Modelos y migraciones
- `categories`: `id`, `name`, `description`, `timestamps`
- `funkos`: `id`, `name`, `category_id` (FK), `era`, `image_path`, `description`, `price`, `timestamps`
- `users`: tabla estándar de Breeze

### Controladores CRUD
Todos con los 7 métodos RESTful: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

| Controlador | Ruta base | Protegido |
|---|---|---|
| `FunkoController` | `/funkos` | ✅ auth |
| `CategoryController` | `/categories` | ✅ auth |
| `UserController` | `/users` | ✅ auth |
| `DashboardController` | `/dashboard` | ✅ auth + verified |

### Validaciones
- Validación inline en controlador con `$request->validate()`
- Mensajes de error en blade con `@error`
- Confirmación de eliminación con **SweetAlert2**
- Mensajes de éxito con `session('success')` en las vistas

### Dashboard
- Tarjetas con contadores: `$funkosCount`, `$categoriesCount`, `$usersCount`
- `DashboardController@index()` pasa los datos a la vista

### Relaciones entre modelos
```php
// Funko → belongsTo → Category
$funko->category->name

// Category → hasMany → Funko
$category->funkos->count()
```

---

## ✅ Sesión 24/03/2026 — Stock + Tabla Orders

### Campo `stock` en `funkos`

**Migración:** `add_stock_to_funkos_table`
```php
$table->unsignedInteger('stock')->default(0)->after('price');
```
- `unsignedInteger` → sin negativos (stock no puede ser -1)
- `default(0)` → todos los funkos existentes arrancan con 0 stock

**Modelo `Funko.php`** — añadido `'stock'` al `$fillable`:
```php
protected $fillable = ['name', 'era', 'image_path', 'description', 'price', 'stock', 'category_id'];
```

### Tabla `orders` + Modelo `Order`

**Migración:** `create_orders_table`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | autoincrement | PK |
| `user_id` | foreignId nullable | Si compra sin login → null. `nullOnDelete()` |
| `name` | string | Nombre del comprador |
| `email` | string | Email de contacto |
| `address` | text | Dirección de envío |
| `total` | decimal(8,2) | Precio total del pedido |
| `status` | string default 'pending' | pending / paid / shipped |
| `timestamps` | — | created_at + updated_at |

**Modelo `Order.php`:**
```php
protected $fillable = ['user_id', 'name', 'email', 'address', 'total', 'status'];

public function user() {
    return $this->belongsTo(User::class); // puede ser null
}
```

---

## ✅ Sesión 27/03/2026 — Checkout completo funcional

### `OrderController` — dos métodos

**`store(Request $request)`** — recibe el formulario de compra:
1. Valida: `name`, `email`, `address`, `cart` (JSON)
2. Decodifica el carrito con `json_decode()`
3. Calcula el total con `collect($cart)->sum()`
4. Guarda el pedido en `orders` con `Order::create()`
5. Descuenta `stock` de cada funko con `$funko->decrement('stock', $qty)` — solo si hay suficiente stock
6. Redirige a la página de confirmación

**`thanks($order)`** — muestra la confirmación con el número de pedido

### Rutas añadidas en `web.php` (públicas, sin auth)
```php
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/checkout/gracias/{order}', [OrderController::class, 'thanks'])->name('checkout.thanks');
```

### Vista `shop/thankyou.blade.php`
- Icono check verde, número de pedido `#{{ $orderId }}`, botón "Volver a la tienda"
- `@push('scripts')` → `localStorage.removeItem('funko_cart')` → limpia el carrito al llegar

### Campo `stock` en formularios del admin
- Añadido `<input name="stock">` en `funkos/create.blade.php` y `funkos/edit.blade.php`
- Añadida regla `'stock' => 'required|integer|min:0'` en `FunkoController@store()` y `update()`
- `update()` ahora incluye `'stock'` en el array `$updateData`

### Estadísticas del hero (tienda)
- Añadido `$funkos->sum('stock')` → muestra "Unidades en stock" (se descuenta en tiempo real)
- Renombrado "Funkos disponibles" → "Modelos de funko" para mayor claridad

---

## ✅ Sesión 30/03/2026 — Admin completo: pedidos, stock y dashboard

### Bootstrap en layout admin
- Añadido CDN Bootstrap 5.3 en `layouts/app.blade.php`
- Motivo: Breeze no incluye Bootstrap por defecto → los badges `bg-danger`, `bg-success`, etc. no tenían color

### Vista `/orders` — Panel admin de pedidos
- `OrderController@index()`: `Order::with('user')->latest()->get()` → lista todos los pedidos
- `OrderController@update()`: Route Model Binding, valida `'status' => 'in:pending,paid,shipped'`
- Ruta `Route::resource('orders', OrderController::class)->only(['index', 'update'])` dentro de `middleware('auth')`
- Vista `resources/views/orders/index.blade.php`:
  - Tabla con: cliente, email, total, estado, fecha
  - Badges coloreados: amarillo=pending, azul=paid, verde=shipped
  - `<select onchange="this.form.submit()">` → cambio de estado instantáneo sin botón extra
  - `@method('PUT')` + `@csrf` + flash `session('success')`
  - `@forelse/@empty` → "No hay pedidos todavía" si está vacío
- Añadido enlace "Pedidos" en `layouts/navigation.blade.php`

### Stock visible en listado de funkos (admin)
- Añadida columna `Stock` al `<thead>` de `funkos/index.blade.php`
- Lógica: `@if($funko->stock > 0)` → número; `@else` → `<span class="badge bg-danger">Sin stock</span>`

### "Agotado" en la tienda pública
- `shop/index.blade.php`: botón condicional en cada card:
  - `@if($funko->stock > 0)` → botón ámbar `add-to-cart` "+ Añadir"
  - `@else` → botón gris con atributo `disabled` y texto "Agotado" (no se puede pulsar)

### Card "Pedidos" en el dashboard
- `DashboardController`: añadido `use App\Models\Order`, `$ordersCount = Order::count()`, `$pendingCount = Order::where('status', 'pending')->count()`
- `dashboard.blade.php`: nueva tarjeta color ámbar con total de pedidos + "X pendientes"
- Grid cambiado de `md:grid-cols-3` a `md:grid-cols-4`

---

## ⏳ Lo que queda por hacer

### 🎨 Panel Admin — Rediseño visual (PRIORIDAD 1 — SIGUIENTE)
- El panel usa Bootstrap/Breeze por defecto (blanco, básico)
- Mejorar con: sidebar de navegación, colores corporativos, cards con iconos
- Mantener Bootstrap (no migrar a Tailwind) — menos riesgo, más rápido

### 🔒 Mejoras opcionales
- [ ] Login/registro opcional en la navbar de la tienda (`@guest` / `@auth`)
- [ ] Paginación en los listados del admin

---

## 🗺️ Ruta de trabajo recomendada (actualizada 30/03/2026)

1. ✅ **CRUD Funkos + Categorías + Usuarios** — Feb 2026
2. ✅ **Autenticación** con Laravel Breeze — Feb 2026
3. ✅ **Campo `stock`** en tabla `funkos` — 24/03
4. ✅ **Tabla `orders`** + Modelo `Order` — 24/03
5. ✅ **`OrderController`** + rutas checkout + vista confirmación — 27/03
6. ✅ **Stock en formularios** create/edit del admin — 27/03
7. ✅ **Admin: vista pedidos** `/orders` + cambiar estado — 30/03
8. ✅ **Admin: Bootstrap** en layout + stock en listado + badge "Sin stock" — 30/03
9. ✅ **Tienda: "Agotado"** en cards si stock = 0 — 30/03
10. ✅ **Dashboard: card "Pedidos"** con total y pendientes — 30/03
11. ⏳ **Admin: rediseño visual** del panel ← SIGUIENTE
12. ⏳ **Tienda: login/registro** opcional en navbar (`@guest`/`@auth`)

---

## 🧠 Conceptos aprendidos

| Concepto | Dónde se aplicó |
|---|---|
| `Route::resource()` → genera 7 rutas RESTful automáticamente | `web.php` |
| `middleware('auth')` → protege grupo de rutas | `web.php` |
| `$fillable` → protección de asignación masiva | Todos los modelos |
| `belongsTo` / `hasMany` → relaciones Eloquent | Funko ↔ Category |
| `compact('var')` → pasar variables a las vistas | Todos los controladores |
| `session('success')` → mensajes flash entre redirecciones | store/update/destroy |
| `SweetAlert2` → confirmación visual de eliminación | Vistas index |
| Nueva migración `--table=` → modifica tabla existente | `add_stock_to_funkos_table` |
| `unsignedInteger()->default(0)` → campo numérico sin negativos | Campo `stock` |
| `foreignId()->nullable()->nullOnDelete()` → FK opcional | Campo `user_id` en orders |
| `make:model -m` → crea modelo + migración a la vez | `Order` |
| `collect($array)->sum()` → suma sobre colección PHP | Cálculo total del pedido |
| `$model->decrement('campo', $n)` → resta directa en DB | Descuento de stock |
| `json_decode($json, true)` → JSON string → array PHP | Carrito recibido del frontend |
| `auth()->id()` → id del usuario autenticado o null | `user_id` en orders |
| `$collection->sum('campo')` → suma un campo de todos los registros | `$funkos->sum('stock')` |

> Este archivo documenta el desarrollo del backend, modelos, migraciones y panel administrativo.
> El progreso del frontend de la tienda pública está en `project_progress_frontend.md`.

---

## 📁 Archivos relevantes

```
app/
  Http/Controllers/
    DashboardController.php   ← estadísticas del panel
    FunkoController.php       ← CRUD funkos + método shop()
    CategoryController.php    ← CRUD categorías
    UserController.php        ← CRUD usuarios
    OrderController.php       ← (pendiente) gestión de pedidos
  Models/
    Funko.php                 ← name, era, image_path, price, stock, category_id
    Category.php              ← name, description
    User.php                  ← Laravel Breeze auth
    Order.php                 ← user_id (nullable), name, email, address, total, status
database/
  migrations/
    create_categories_table.php
    create_funkos_table.php
    add_stock_to_funkos_table.php   ← añadido 24/03
    create_orders_table.php         ← añadido 24/03
routes/
  web.php                     ← rutas protegidas con middleware('auth')
  auth.php                    ← rutas de Laravel Breeze
resources/views/
  layouts/
    app.blade.php             ← layout del panel admin
    navigation.blade.php      ← navbar del panel admin
  dashboard.blade.php         ← estadísticas
  funkos/                     ← vistas CRUD
  categories/                 ← vistas CRUD
  users/                      ← vistas CRUD
```

---

## ✅ Sesiones anteriores (Feb 2026) — Panel Admin completo

### Autenticación
- Laravel Breeze instalado → login, registro, logout, dashboard
- Rutas protegidas con `middleware(['auth', 'verified'])` y `middleware('auth')`

### Modelos y migraciones
- `categories`: `id`, `name`, `description`, `timestamps`
- `funkos`: `id`, `name`, `category_id` (FK), `era`, `image_path`, `description`, `price`, `timestamps`
- `users`: tabla estándar de Breeze

### Controladores CRUD
Todos con los 7 métodos RESTful: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

| Controlador | Ruta base | Protegido |
|---|---|---|
| `FunkoController` | `/funkos` | ✅ auth |
| `CategoryController` | `/categories` | ✅ auth |
| `UserController` | `/users` | ✅ auth |
| `DashboardController` | `/dashboard` | ✅ auth + verified |

### Validaciones
- `FormRequest` para Funkos y Categorías → reglas de validación separadas del controlador
- Mensajes de error en blade con `@error`
- Confirmación de eliminación con **SweetAlert2**
- Mensajes de éxito con `session('success')` en las vistas

### Dashboard
- Tarjetas con contadores: `$funkosCount`, `$categoriesCount`, `$usersCount`
- `DashboardController@index()` pasa los datos a la vista

### Relaciones entre modelos
```php
// Funko → belongsTo → Category
$funko->category->name

// Category → hasMany → Funko
$category->funkos->count()
```

---

## ✅ Sesión 24/03/2026 — Stock + Tabla Orders

### Campo `stock` en `funkos`

**Migración:** `add_stock_to_funkos_table`
```php
$table->unsignedInteger('stock')->default(0)->after('price');
```
- `unsignedInteger` → sin negativos (stock no puede ser -1)
- `default(0)` → todos los funkos existentes arrancan con 0 stock

**Modelo `Funko.php`** — añadido `'stock'` al `$fillable`:
```php
protected $fillable = ['name', 'era', 'image_path', 'description', 'price', 'stock', 'category_id'];
```

---

### Tabla `orders` + Modelo `Order`

**Migración:** `create_orders_table`

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | autoincrement | PK |
| `user_id` | foreignId nullable | Si compra sin login → null. `nullOnDelete()` |
| `name` | string | Nombre del comprador |
| `email` | string | Email de contacto |
| `address` | text | Dirección de envío |
| `total` | decimal(8,2) | Precio total del pedido |
| `status` | string default 'pending' | pending / paid / shipped |
| `timestamps` | — | created_at + updated_at |

**Modelo `Order.php`:**
```php
protected $fillable = ['user_id', 'name', 'email', 'address', 'total', 'status'];

public function user() {
    return $this->belongsTo(User::class); // puede ser null
}
```

**Por qué `user_id` nullable:**  
La tienda permite comprar sin estar registrado. Si el usuario se elimina, el pedido no se borra — solo queda `user_id = null`.

---

## ⏳ Lo que queda por hacer

### 🛒 Checkout (siguiente prioridad)
- [ ] `OrderController` con método `store()` → recibe formulario → guarda pedido en `orders`
- [ ] Ruta `POST /checkout` → sin auth requerida
- [ ] Descuento de `stock` al confirmar pedido
- [ ] Página de confirmación → limpia localStorage del carrito
- [ ] Validación del formulario (nombre, email, dirección requeridos)

### 📦 Panel Admin — Gestión de pedidos
- [ ] Vista `/orders` → listado de pedidos con estado (pending/paid/shipped)
- [ ] Cambiar estado del pedido desde el admin
- [ ] Dashboard: añadir tarjeta con total de pedidos + pendientes

### 🔒 Mejoras opcionales
- [ ] Mostrar campo `stock` en el CRUD de Funkos (formulario create/edit)
- [ ] Mostrar "Agotado" en la tienda si `stock === 0`
- [ ] Login/registro opcional en la navbar de la tienda (`@guest` / `@auth`)
- [ ] Paginación en los listados del admin

---

## 🗺️ Ruta de trabajo recomendada (actualizada 24/03/2026)

1. ✅ **CRUD Funkos + Categorías + Usuarios** — completado Feb 2026
2. ✅ **Autenticación** con Laravel Breeze — completado Feb 2026
3. ✅ **Campo `stock`** en tabla `funkos` — completado 24/03
4. ✅ **Tabla `orders`** + Modelo `Order` — completado 24/03
5. ⏳ **`OrderController@store()`** + ruta `POST /checkout` ← siguiente
6. ⏳ **Panel admin**: vista listado de pedidos `/orders`
7. ⏳ **Stock**: mostrar en formulario admin + "Agotado" en tienda
8. ⏳ **Login opcional** en navbar de la tienda

---

## 🧠 Conceptos aprendidos

| Concepto | Dónde se aplicó |
|---|---|
| `Route::resource()` → genera las 7 rutas RESTful automáticamente | `web.php` |
| `middleware('auth')` → protege grupo de rutas | `web.php` |
| `FormRequest` → validación separada del controlador | `FunkoRequest`, `CategoryRequest` |
| `$fillable` → protección de asignación masiva | Todos los modelos |
| `belongsTo` / `hasMany` → relaciones Eloquent | Funko ↔ Category |
| `compact('var')` → pasar variables a las vistas | Todos los controladores |
| `session('success')` → mensajes flash entre redirecciones | store/update/destroy |
| `SweetAlert2` → confirmación visual de eliminación | Vistas index |
| Nueva migración para modificar tabla existente (`--table=`) | `add_stock_to_funkos_table` |
| `unsignedInteger()->default(0)` → campo numérico sin negativos | Campo `stock` |
| `foreignId()->nullable()->nullOnDelete()` → FK opcional | Campo `user_id` en orders |
| `make:model -m` → crea modelo + migración a la vez | `Order` |
4. Mejorar el diseño visual y la experiencia de usuario.
5. (Opcional) Añadir funcionalidades avanzadas (API, roles, etc.).

---

**¡Gran trabajo hasta ahora! El proyecto está muy avanzado y solo faltan los últimos detalles para tener una tienda funcional y profesional.**
