# Patrones de Diseño en EventCore

En el desarrollo del sistema EventCore, se han identificado y aplicado patrones de diseño fundamentales que garantizan la escalabilidad, el mantenimiento y la organización del código siguiendo las mejores prácticas de la ingeniería de software y el framework Laravel.

## 1. Patrón Singleton (Instancia Única)

### Qué problema resuelve
En una aplicación web, existen objetos que deben tener una única instancia compartida en todo el ciclo de vida de la petición para evitar el consumo excesivo de memoria y conflictos de estado. Por ejemplo, la conexión a la Base de Datos o el Service Container de Laravel.

### Implementación en EventCore
Laravel utiliza el patrón **Singleton** para manejar su contenedor de servicios. 
- **Ejemplo técnico**: Cuando usamos `DB::table(...)` o `auth('admin')`, el sistema no crea una nueva conexión o manejador de sesión cada vez; en su lugar, recupera la instancia única ya existente.
- **Mejora**: Asegura que todas las operaciones de base de datos se realicen sobre la misma conexión, optimizando el rendimiento y la integridad de las transacciones.

## 2. Patrón Facade (Fachada)

### Qué problema resuelve
Provee una interfaz simplificada (una "fachada") para un sistema complejo de clases. Permite acceder a funcionalidades robustas mediante llamadas estáticas legibles sin necesidad de instanciar manualmente múltiples dependencias.

### Implementación en EventCore
El sistema utiliza intensivamente fachadas para tareas críticas:
- **`Hash::make()`**: Fachada para el sistema de hachado de contraseñas de alta seguridad.
- **`Route::get()`**: Fachada para el motor de enrutamiento.
- **`Auth::guard()`**: Fachada para el sistema de autenticación.
- **Mejora**: El código es mucho más limpio y fácil de leer. En lugar de inyectar complejos gestores de autenticación, usamos una interfaz estática expresiva.

## 3. Patrón MVC (Modelo-Vista-Controlador)

### Qué problema resuelve
La mezcla de lógica de negocio, acceso a datos y presentación visual genera código "espagueti" difícil de mantener.

### Implementación en EventCore
- **Modelos (`App\Models`)**: Representan la estructura de datos (Ej: `UsuarioAdmin`).
- **Controladores (`App\Http\Controllers`)**: Orquestan la lógica (Ej: `EventoController`).
- **Vistas (`resources/views`)**: Renderizan la interfaz final mediante Blade.
- **Mejora**: Permite trabajar de forma aislada en cada capa. Podemos cambiar el diseño (Vista) sin afectar la lógica de cancelación de eventos (Controlador).

---
*Documentación técnica preparada para el informe final de arquitectura de EventCore.*
