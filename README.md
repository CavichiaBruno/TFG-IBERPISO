# IberPiso

Guía de instalación y ejecución para el proyecto `iberpiso`.

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- Git (opcional)

## Qué hace este proyecto

Este repositorio es una aplicación Laravel 13 con backend en PHP + SQLite local por defecto y frontend gestionado por Vite.

## Configuración del entorno

1. Copia el archivo de ejemplo:

```bash
cp .env.example .env
```

2. Genera el `APP_KEY` de Laravel:

```bash
php artisan key:generate
```

3. Si estás usando SQLite local, crea el archivo de base de datos (si aún no existe):

```bash
touch database/database.sqlite
```

4. Revisa el `.env`:

- `DB_CONNECTION=sqlite` indica que la app usa SQLite local.
- No necesitas Neon DB a menos que cambies esta configuración.
- `APP_KEY` deberá quedar con un valor `base64:...` después de ejecutar `php artisan key:generate`.

### Ejemplo mínimo de `.env`

```env
APP_NAME=IberPiso
APP_ENV=local
APP_KEY=base64:XXXXXXXXXXXXX
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## Instalación

Desde la raíz del proyecto ejecuta:

```bash
composer install
npm install
```

## Preparar la base de datos

Ejecuta migraciones para crear las tablas necesarias:

```bash
php artisan migrate
```

Si el proyecto incluye seeders y quieres cargar datos de ejemplo:

```bash
php artisan db:seed
```

## Compilar activos

Para desarrollo con recarga en caliente:

```bash
npm run dev
```

Para compilar los activos para producción:

```bash
npm run build
```

## Ejecutar la aplicación

Para levantar el servidor local de Laravel:

```bash
php artisan serve
```

Luego abre en el navegador:

```text
http://127.0.0.1:8000
```

## Comandos útiles

- `php artisan migrate`: ejecutar migraciones
- `php artisan migrate:rollback`: deshacer la última migración
- `php artisan db:seed`: ejecutar seeders
- `php artisan key:generate`: generar clave de aplicación
- `npm run dev`: levantar Vite para desarrollo
- `npm run build`: compilar los assets

## Notas importantes

- El `APP_KEY` es obligatorio para encriptar cookies, sesiones y otros datos internos de Laravel.
- Si no quieres usar SQLite, cambia `DB_CONNECTION` a `mysql`, `pgsql` o lo que necesites y configura `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` en el `.env`.
- No compartas tu `.env` con claves sensibles en repositorios públicos.

## Estructura básica

- `app/`: lógica principal de Laravel
- `database/`: migraciones, seeders y fixtures
- `public/`: entrada pública y assets compilados
- `resources/`: vistas, CSS y JavaScript fuente
- `routes/`: rutas de la aplicación

## Licencia

Este proyecto usa la licencia MIT.
