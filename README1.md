# Funko Football Collection - Estructura de Base de Datos

Este documento muestra la **estructura de las tablas** que tendrá nuestro proyecto.

---

## Tabla: `funkos`

- `id` (clave primaria)
- `name` - Nombre del Funko
- `team` - Equipo del jugador (opcional)
- `era` - Década / época del Funko (opcional)
- `image_path` - Ruta de la imagen (opcional)
- `description` - Descripción (opcional)
- `category_id` - Relación con la categoría (opcional)
- `created_at` - Fecha de creación automática
- `updated_at` - Fecha de actualización automática

---

## Tabla: `categories`(clasificar funkos)

- `id` (clave primaria)
- `name` - Nombre de la categoría
- `description` - Descripción (opcional)
- `created_at` - Fecha de creación automática
- `updated_at` - Fecha de actualización automática

---

## Tabla: `users`(gestionar usuarios)

- `id` (clave primaria)
- `name` - Nombre del usuario
- `email` - Correo electrónico
- `password` - Contraseña (hash)
- `role ` -  ejemplo: admin o user
- `created_at` - Fecha de creación automática
- `updated_at` - Fecha de actualización automática

---

## Tabla: `orders`(compras / colecciones de funkos)

- `id` (clave primaria)
- `user_id` - Relación con el usuario
- `total` - Total de la orden
- `status` -  ver estado pendiente, completado, cancelado
- `created_at` - Fecha de creación automática
- `updated_at` - Fecha de actualización automática

---

## Tabla: `order_items`(detalle de cada funko comprado)

- `id` (clave primaria)
- `order_id` - Relación con la orden
- `funko_id` - Relación con el Funko
- `quantity` - Cantidad
- `price` - Precio al momento de la compra
- `created_at` - Fecha de creación automática
- `updated_at` - Fecha de actualización automática
