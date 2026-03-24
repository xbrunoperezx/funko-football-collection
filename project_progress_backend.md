# Progreso Backend — Panel Admin + API interna (`/dashboard`, `/funkos`, `/categories`, `/users`, `/orders`)

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
