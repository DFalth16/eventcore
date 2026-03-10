# Guía de Pruebas API con Postman - EventCore

Esta guía detalla los pasos para probar los endpoints de la API y capturar las evidencias para el informe.

## 1. Autenticación (Login)
**Objetivo:** Obtener el token de acceso.

- **Método:** `POST`
- **URL:** `{{base_url}}/api/login`
- **Body (raw JSON):**
```json
{
    "email": "admin@eventcore.com",
    "password": "admin"
}
```
> [!TIP]
> Captura la respuesta JSON donde se vea el campo `"token"`.

---

## 2. Listar Eventos (Read)
**Objetivo:** Verificar el acceso protegido y la recuperación de datos.

- **Método:** `GET`
- **URL:** `{{base_url}}/api/eventos`
- **Headers:**
    - `Authorization: Bearer <TU_TOKEN>`
    - `Accept: application/json`

---

## 3. Crear Registro (Create)
**Objetivo:** Demostrar la inserción de datos vía API.

- **Método:** `POST`
- **URL:** `{{base_url}}/api/eventos`
- **Body (raw JSON):**
```json
{
    "titulo": "Evento de Prueba Postman",
    "id_categoria": 1,
    "id_sede": 1,
    "fecha_inicio": "2026-05-20 09:00:00",
    "fecha_fin": "2026-05-20 18:00:00",
    "cupo_maximo": 100,
    "descripcion": "Creado desde Postman para el informe",
    "precio_entrada": 0,
    "es_gratuito": true
}
```

---

## 4. Actualizar Registro (Update)
**Objetivo:** Probar la modificación de un recurso existente.

- **Método:** `PUT` (o `PATCH`)
- **URL:** `{{base_url}}/api/eventos/{id}` (reemplaza {id} por el id del evento creado)
- **Body (raw JSON):**
```json
{
    "titulo": "Evento Actualizado vía API",
    "id_categoria": 1,
    "fecha_inicio": "2026-05-21 10:00:00",
    "fecha_fin": "2026-05-21 19:00:00",
    "descripcion": "Descripción modificada correctamente"
}
```

---

## 5. Eliminar Registro (Delete)
**Objetivo:** Verificar la eliminación/cancelación lógica.

- **Método:** `DELETE`
- **URL:** `{{base_url}}/api/eventos/{id}`

---
### Resumen de Endpoints Probados
| Funcionalidad | Método | Path |
|---|---|---|
| Login | `POST` | `/api/login` |
| Listar | `GET` | `/api/eventos` |
| Crear | `POST` | `/api/eventos` |
| Actualizar | `PUT` | `/api/eventos/{id}` |
| Eliminar | `DELETE` | `/api/eventos/{id}` |
