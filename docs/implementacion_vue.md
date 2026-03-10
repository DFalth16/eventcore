# Implementación de Frontend con Vue.js - EventCore

En esta sección se detalla la integración de Vue.js en el sistema EventCore para mejorar la interactividad y la experiencia del usuario, cumpliendo con los requisitos de modernización del frontend.

## 1. Integración de Tecnologías

Se ha implementado **Vue 3** mediante el uso de la **Composition API**, permitiendo una gestión de estado reactiva y eficiente. El sistema utiliza:
- **Vue.js 3**: Framework progresivo para la interfaz de usuario.
- **Axios**: Librería para realizar peticiones HTTP asíncronas de forma sencilla.
- **Bootstrap Icons**: Para una iconografía moderna y coherente.

## 2. Funcionalidades Implementadas

La implementación se centró en el módulo de **Gestión de Eventos**, proporcionando una interfaz dinámica sin recargas de página.

### Listado Reactivo y Filtrado en Tiempo Real
- **Componente de Búsqueda**: Se desarrolló una barra de búsqueda reactiva que utiliza una propiedad computada (`filteredEvents`) para filtrar eventos por título, código o sede instantáneamente mientras el usuario escribe.
- **Estado de Carga**: Implementación de un *spinner* animado con micro-animaciones CSS para informar al usuario durante el consumo de datos.

### Visualización Dinámica de Ocupación
- Se crearon funciones reactivas (`getOcupacion` y `getOcupacionColor`) para calcular y mostrar barras de progreso dinámicas que cambian de color (Cian -> Ámbar -> Rosa) según el porcentaje de inscritos, proporcionando un feedback visual inmediato sobre el éxito del evento.

### Gestión de Registros (CRUD)
- **Eliminación (Cancelación)**: Implementación de la función `cancelEvent` que interactúa de forma segura con la API mediante el método `DELETE`, con diálogos de confirmación integrados en el flujo de Vue.
- **Navegación Fluida**: Enlaces dinámicos para edición y visualización de inscritos que mantienen el contexto de la aplicación.

## 3. Interacción con el Usuario (UX)

- **Reactividad**: Toda la tabla de eventos responde inmediatamente a las acciones del usuario.
- **Validación Visual**: El uso de la directiva `v-cloak` asegura que no haya parpadeos de plantillas sin renderizar al cargar la página.
- **Micro-interacciones**: Transiciones suaves y cambios de estado claros para mejorar la sensación de "Premium" del sistema.

## 4. Consumo de la API

El frontend se comunica con el sistema mediante endpoints RESTful:
- `GET /api/eventos`: Para obtener la lista completa de eventos y sus relaciones.
- `DELETE /api/eventos/{id}`: Para cancelar eventos de forma remota.

La lógica de consumo está centralizada en la función asíncrona `fetchEvents`, que maneja errores de red y estados de carga de forma robusta.

---
*Documentación generada para el informe técnico de EventCore, demostrando la capacidad de escalabilidad y modernización del sistema.*
