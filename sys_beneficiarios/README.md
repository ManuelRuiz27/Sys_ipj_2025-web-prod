# Sys IPJ 2025 — Módulo Beneficiarios

Aplicación Laravel 11 para la gestión y registro de beneficiarios, con autenticación (Breeze), roles (Spatie Permission), paneles con KPIs y carga de catálogos (municipios y secciones). Se ejecuta en Docker (PHP-FPM + Nginx + MySQL + Node).

- Código de este módulo: este directorio (`sys_beneficiarios/`)
- Orquestación Docker: `../docker-compose.yml`
- Nginx: `../.docker/nginx/default.conf`

## Arranque rápido (Docker)

1) Variables de entorno:

```
cp .env.example .env
```

Revisa en `.env` (valores por defecto para Docker):
- `APP_URL=http://localhost`
- `DB_CONNECTION=mysql`
- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE=sys_beneficiarios`
- `DB_USERNAME=root`
- `DB_PASSWORD=secret`

2) Levanta contenedores desde la raíz del repo:

```
# Ejecutar desde el directorio raíz del repo
cd ..
docker compose up -d --build
```

3) Inicializa la app (clave, migraciones, seeders, assets):

```
# Dentro del contenedor app
docker compose exec app php artisan key:generate
# Migraciones + seeders base (roles, admin y catálogos si hay CSVs)
docker compose exec app php artisan migrate --seed
# Compilación de assets
docker compose exec node npm install
docker compose exec node npm run build
```

4) Acceso web:

- URL: `http://localhost`
- Usuario admin por defecto: `admin@example.com` / `Password123`

Servicios en Docker:
- `app`: PHP-FPM 8.3 (Laravel)
- `nginx`: sirve `public/` en puerto 80
- `mysql`: MySQL 8 (DB `sys_beneficiarios`, credenciales root/secret)
- `node`: Node 20 para Vite

## Catálogos (Municipios y Secciones)

Coloca archivos CSV en `database/seeders/data/`:
- `municipios.csv` con columnas: `clave,nombre`
- `secciones.csv` con columnas: `seccional,distrito_local,distrito_federal` y una de `municipio_id` o `municipio_clave`

Importa desde el contenedor `app`:

```
docker compose exec app php artisan catalogos:import --path=database/seeders/data
```

Opciones útiles:
- `--fresh` limpia tablas antes de importar
- `--sql=/ruta/a/archivo.sql` ejecuta SQL previo a la importación

## Rutas y roles (resumen)

- Admin:
  - Panel: `/admin`
  - KPIs: `/admin/kpis`
  - Usuarios: `/admin/usuarios`
  - Beneficiarios: `/admin/beneficiarios` (incluye export)
  - Catálogos: `/admin/catalogos`
- Encargado:
  - Panel: `/encargado`
  - KPIs: `/encargado/kpis`
  - Beneficiarios: `/encargado/beneficiarios` (listado/detalle/export)
- Capturista:
  - Panel: `/capturista`
  - KPIs personales: `/capturista/kpis` (alias: `/mi-progreso/kpis`)
  - Mis registros: `/mis-registros`
- Recursos comunes: `beneficiarios` y `domicilios` (según rol)
- API pública: `GET /api/secciones/{seccional}` (throttle 30/min)

Código fuente relevante:
- Rutas web: `routes/web.php`
- API: `routes/api.php` y `app/Http/Controllers/Api/SeccionesController.php`
- Dashboard/KPIs: `app/Http/Controllers/DashboardController.php`

## Desarrollo

- Vite en modo dev (hot reload):

```
docker compose exec node npm run dev
```

- Artisan y pruebas:

```
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan test
```

## Pruebas

Pruebas en `tests/` (Feature y Unit). Ejecuta:

```
docker compose exec app php artisan test
```

## Guía para nuevas funcionalidades

- Rutas: agrega en `routes/web.php` (o `routes/api.php`).
- Controladores: crea en `app/Http/Controllers/...` y asigna middleware de rol si aplica.
- Modelos/Migraciones: en `app/Models` y `database/migrations` (usa `php artisan make:model -m`).
- Vistas: en `resources/views` (layouts, parciales y vistas por rol).
- Permisos/Roles: usa `spatie/laravel-permission` y seeders para roles nuevos.
- Pruebas: agrega en `tests/Feature`/`tests/Unit` cubriendo rutas, políticas y flujos.
- Frontend: JS/SCSS en `resources/js` y `resources/scss` (compila con Vite).

## Despliegue

- Ajusta `server_name` en `../.docker/nginx/default.conf`.
- Configura `.env` con `APP_ENV=production`, `APP_DEBUG=false` y `APP_URL`.
- Compila assets y cachea configuración/rutas/vistas.
- Más detalles: consulta `../docs/despliegue.md`.

## Troubleshooting

- Logs de Laravel: `storage/logs/`
- Nginx: `/var/log/nginx/error.log` y `access.log`
- MySQL: volumen `db_data` del compose
- Guía: `../docs/troubleshooting.md`

## Roadmap

- Exportaciones avanzadas: CSV/Excel con columnas seleccionables y filtros guardados.
- Asignación de municipios a encargados desde UI (búsqueda, asignación masiva).
- Wizard de importación de catálogos con validación previa y modo "dry-run".
- Auditoría detallada por registro (diff de cambios via activity log) y vistas dedicadas.
- Notificaciones (correo y en-app) para eventos clave: nuevos registros, asignaciones, errores de importación.
- API con tokens personales (lectura de catálogos y beneficiarios) y documentación OpenAPI.
- Seguridad: 2FA opcional para usuarios y políticas de password endurecidas.
- Eliminación lógica (soft deletes) y papelera para restaurar beneficiarios.
- Observabilidad: más métricas de KPIs y endpoints de salud.
- Calidad: ampliar cobertura de pruebas y escenarios e2e de flujos críticos.

## Changelog

### [Unreleased]
- Nuevas exportaciones y filtros avanzados en listados.
- UI para asignaciones de municipios a encargados.
- Import wizard con validaciones y "dry-run".
- Endpoints API autenticados por token (solo lectura).

### 0.1.0 — Base inicial
- Autenticación con Breeze y roles con Spatie Permission.
- CRUDs base de beneficiarios y domicilios.
- Paneles y KPIs por rol (admin, encargado, capturista).
- Importación de catálogos (municipios y secciones) vía comando artisan.
- Infra de Docker (app, nginx, mysql, node) y build de assets con Vite.

## Licencia

Proyecto interno del equipo. Uso restringido según políticas vigentes.
