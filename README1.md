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


# "Seguridad: 
- 'Implementada protección contra CSRF (usando los middlewares de Laravel)' 
- y validación estricta de inputs para prevenir XSS

 # 10 principales vulnerabilidades de seguridad web según la lista de OWASP Top 10::
- Broken Access Control (Control de acceso roto): Los usuarios pueden acceder a recursos fuera de sus permisos.
- Security Misconfiguration (Configuración de seguridad incorrecta): Ajustes inseguros por defecto, configuraciones abiertas o errores en los mensajes.
- Software Supply Chain Failures (Fallos en la cadena de suministro de software): Uso de librerías, dependencias o componentes vulnerables.
- Cryptographic Failures (Fallos criptográficos): Exposición de datos sensibles por falta de cifrado o uso de algoritmos débiles.
- Injection (Inyección): Inserción de código malicioso (SQL, NoSQL, OS) que se ejecuta sin validación.
- Insecure Design (Diseño inseguro): Fallos en la arquitectura y diseño que no pueden solucionarse solo con implementación.
- Authentication Failures (Fallos de autenticación): Identificación incorrecta de usuarios, permitiendo ataques de fuerza bruta.
- Software or Data Integrity Failures (Fallos de integridad de datos y software): Cambios en software o datos sin validación (ej. plugins maliciosos).
- Security Logging and Alerting Failures (Fallos en el registro y monitoreo de seguridad): Falta de detección, respuesta y registro de incidentes.
- Mishandling of Exceptional Conditions (Manejo incorrecto de condiciones excepcionales)