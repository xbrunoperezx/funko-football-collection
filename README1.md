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



