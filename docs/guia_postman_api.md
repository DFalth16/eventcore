# Guía de Pruebas API - EventCore

Esta guía explica cómo utilizar **Postman** (o herramientas similares como Insomnia/Curl) para autenticarse y gestionar eventos.

## 1. Obtención del Token (Login)

Para obtener tu Bearer Token, utiliza tus mismas credenciales del sistema administrativo.

- **Método:** `POST`
- **URL:** `http://localhost:8000/api/login`
- **Cabeceras:**
  - `Content-Type: application/json`
- **Cuerpo (JSON):**
```json
{
    "email": "tu_usuario@ejemplo.com",
    "password": "tu_password"
}
```

**Respuesta Exitosa:**
```json
{
    "success": true,
    "message": "Login exitoso",
    "data": {
        "user": { ... },
        "token": "UN_TOKEN_MUY_LARGO_DE_80_CARACTERES"
    }
}
```
*Copia el valor del campo `token`.*

## 2. Acceso a Rutas Protegidas (Ejemplo: Listar Eventos)

Una vez que tienes el token, debes enviarlo en todas las peticiones a la API.

- **Método:** `GET`
- **URL:** `http://localhost:8000/api/eventos`
- **Cabeceras:**
  - `Authorization: Bearer <TU_TOKEN_AQUÍ>`
  - `Accept: application/json`

## 3. CRUD de Eventos

Todas las rutas protegidas requieren el mismo esquema de autorización.

| Operación | Método | Endpoint | Cabecera Auth |
|---|---|---|---|
| Listar | `GET` | `/api/eventos` | `Bearer <token>` |
| Ver Uno | `GET` | `/api/eventos/{id}` | `Bearer <token>` |
| Crear | `POST` | `/api/eventos` | `Bearer <token>` |
| Actualizar| `PUT` | `/api/eventos/{id}` | `Bearer <token>` |
| Eliminar | `DELETE`| `/api/eventos/{id}` | `Bearer <token>` |

---
*Nota: Si el token es inválido o no se envía, el sistema devolverá un error `401 Unauthorized`.*
