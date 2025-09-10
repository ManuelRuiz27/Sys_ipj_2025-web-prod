# API externa

## GET /api/secciones/{seccional}

- Límites: `throttle:30,1` (30 solicitudes por minuto)
- Path param: `seccional` (string exacto)
- Respuesta 200:

```
{
  "municipio_id": 12,
  "distrito_local": "05",
  "distrito_federal": "02"
}
```

- Respuesta 404: cuando no existe la seccional

Implementación: `sys_beneficiarios/app/Http/Controllers/Api/SeccionesController.php`

