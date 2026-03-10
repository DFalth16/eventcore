# Documentación de Seguridad y Autenticación - EventCore

Esta sección detalla los mecanismos de seguridad implementados en el sistema EventCore para garantizar la integridad de los datos y la protección de los usuarios.

## 1. Sistema de Autenticación

El sistema cuenta con dos capas de autenticación adaptadas a sus necesidades:

### Login de Usuarios Administrativos
- **Acceso:** Mediante un formulario web seguro en `/login`.
- **Sesiones:** Laravel maneja sesiones seguras basadas en archivos/base de datos, con regeneración de ID en cada inicio de sesión para prevenir el secuestro de sesión (*Session Hijacking*).

### Autenticación de API (Bearer Token)
- **Generación:** Al autenticarse en el endpoint `POST /api/login`, el sistema genera un token aleatorio de 80 caracteres.
- **Uso:** El cliente debe enviar este token en la cabecera de cada petición: `Authorization: Bearer <token>`.
- **Revocación:** El token puede ser revocado mediante `POST /api/logout`, invalidando el acceso inmediato.

## 2. Protección de Rutas (Middleware)

Se utilizan *Middlewares* personalizados para filtrar el tráfico y asegurar que solo personal autorizado acceda a recursos específicos:
- `auth.admin`: Protege el panel web, redirigiendo al login si no hay sesión activa.
- `api.token`: Intercepta peticiones API, verificando la validez del *Bearer Token*.
- `role`: Controla el acceso basado en niveles (SuperAdmin, Admin, Editor).

## 3. Seguridad de Datos y Contraseñas

### Hashing de Contraseñas
- **Estandarización:** Se utiliza el Facade `Illuminate\Support\Facades\Hash` de Laravel en todos los controladores de autenticación (`AuthController`, `UsuarioController`, `ApiAuthController`).
- **Algoritmo:** Todas las contraseñas se gestionan mediante `Hash::make()` y se verifican con `Hash::check()`, asegurando el uso de Bcrypt o Argon2 según la configuración del framework.
- **Proceso:** El sistema nunca almacena la contraseña en texto plano, cumpliendo con los estándares de seguridad de la industria.

### Validación de Datos Exhaustiva
- Todas las entradas del usuario son validadas mediante `Request::validate()` o el `Validator` factory de Laravel.
- Se implementan reglas estrictas para emails, longitudes de texto y tipos de datos numéricos para prevenir desbordamientos o datos malformados.

## 4. Mecanismos de Protección Contra Ataques Comunes

### SQL Injection
- **Prevención:** Uso sistemático del ORM **Eloquent** y el **Query Builder** de Laravel, que utilizan sentencias preparadas (PDO) internamente, haciendo imposible la inyección de código SQL malicioso.

### CSRF (Cross-Site Request Forgery)
- **Protección Web:** Cada formulario web incluye un token CSRF único mediante `@csrf` que Laravel valida automáticamente para confirmar que la petición proviene del sitio legítimo.
- **Protección API:** Al usar tokens Bearer, las rutas API son inherentemente seguras contra CSRF ya que no dependen de cookies de sesión para la autenticación.

### Manejo Seguro de Sesiones
- Uso de cookies con atributos `HttpOnly` y `SameSite=Lax` por defecto, reduciendo el riesgo de ataques XSS dirigidos a las sesiones.

---
*Documentación generada para el informe de seguridad del proyecto EventCore.*
