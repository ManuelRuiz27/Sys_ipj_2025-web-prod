# Roles y accesos

## Admin

- Acceso a dashboard: `/admin` y KPIs en `/admin/kpis`
- Gestión de usuarios: `/admin/usuarios`
- Gestión de beneficiarios (listado/detalle/export): `/admin/beneficiarios`
- Importación de catálogos: `/admin/catalogos`

## Encargado

- Acceso a dashboard: `/encargado` y KPIs en `/encargado/kpis`
- Listado/detalle/export de beneficiarios: `/encargado/beneficiarios`
- Alcance puede limitarse por municipios asignados (ver `User::municipiosAsignados`)

## Capturista

- Panel personal: `/capturista`
- KPIs personales: `/capturista/kpis` (alias: `/mi-progreso/kpis`)
- Mis registros (CRUD limitado): `/mis-registros`

## Recursos comunes

- CRUD `beneficiarios` y `domicilios` (según rol)
- Perfil de usuario: `/profile`

