# IberPiso - Guía Completa de Instalación y Ejecución

## 📋 Requisitos

- **PHP 8.3** o superior
- **Composer**
- **Node.js** y **npm**
- **Git** (opcional)
- **PowerShell** (en Windows)

Verifica que tengas todo instalado:
```powershell
php --version
composer --version
node --version
npm --version
```

## 🚀 Pasos de Instalación (SQLite Local)

Este proyecto usa **SQLite por defecto**, sin necesidad de base de datos externa.

### 1. Copiar archivo de configuración
```powershell
cp .env.example .env
```

### 2. Generar APP_KEY de Laravel
```powershell
php artisan key:generate
```

### 3. Crear archivo de base de datos SQLite
```powershell
New-Item -Path database/database.sqlite -ItemType File -Force
```

### 4. Instalar dependencias PHP
```powershell
composer install
```

### 5. Instalar dependencias Node.js
```powershell
npm install
```

### 6. Ejecutar migraciones (crear tablas)
```powershell
php artisan migrate
```

### 7. (Opcional) Cargar datos de ejemplo
```powershell
php artisan db:seed
```

## 💻 Ejecutar la Aplicación

Necesitas **2 terminales** abiertas simultáneamente:

### Terminal 1 - Dev Server de Vite (Hot Reload)
```powershell
npm start
```

### Terminal 2 - Servidor Laravel
```powershell
php artisan serve
```

Luego abre en el navegador:
```
http://localhost:8000
```

## 📦 Compilar para Producción

```powershell
npm run build
```

## ⚙️ Configuración `.env` (Valores por defecto)

```env
APP_NAME=IberPiso
APP_ENV=local
APP_KEY=base64:XXXXXXXXXXXXX (se genera automáticamente)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
```

##  Verificar que Todo Funciona

1.  `npm install` - Sin errores
2.  `composer install` - Sin errores
3.  `php artisan migrate` - Migraciones completadas
4.  `npm start` - Dev server activo
5.  `php artisan serve` - Servidor Laravel activo
6.  Abre `http://localhost:8000` en el navegador - Sin errores en consola

## 🔧 Troubleshooting

| Problema | Solución |
|----------|----------|
| "database.sqlite no existe" | Ejecutar: `New-Item -Path database/database.sqlite -ItemType File -Force` |
| "APP_KEY no definido" | Ejecutar: `php artisan key:generate` |
| "Composer no instaló dependencias" | Ejecutar: `composer install` |
| "npm no instaló dependencias" | Ejecutar: `npm install` |
| "Puerto 8000 en uso" | Especificar otro: `php artisan serve --port=8001` |

## 📁 Estructura del Proyecto

```
├── app/                      # Código PHP (Modelos, Controladores, Servicios)
├── config/                   # Configuración de la app
├── database/
│   ├── migrations/           # Migraciones SQL
│   ├── seeders/             # Datos de ejemplo
│   └── database.sqlite      # BD (creada en el paso 3)
├── public/                   # Archivos públicos (CSS, JS compilado)
├── resources/
│   ├── css/                 # Estilos CSS
│   ├── js/                  # JavaScript
│   └── views/               # Vistas Blade
├── routes/                   # Rutas de la aplicación
├── storage/                  # Archivos generados (logs, caché)
├── tests/                    # Tests unitarios y funcionales
├── .env                      # Variables de entorno (creado en paso 1)
├── composer.json             # Dependencias PHP
├── package.json              # Dependencias Node.js
├── vite.config.js            # Configuración de Vite
└── README.md                 # Este archivo
```

## 🔗 Comandos Útiles

```powershell
# Crear nuevo modelo con migración
php artisan make:model NombreModelo -m

# Crear controlador
php artisan make:controller NombreControlador

# Ver todas las rutas
php artisan route:list

# Vaciar caché
php artisan cache:clear

# Ejecutar tests
php artisan test

# Ver logs en tiempo real
php artisan pail

# Reiniciar base de datos (borra todo)
php artisan migrate:fresh --seed
```

---

**Última actualización:** 15 de Mayo de 2026

**Versiones utilizadas:**
- Laravel 11.0
- PHP 8.2+
- Node.js LTS
- Vite
- SQLite 3
- PostgreSQL
