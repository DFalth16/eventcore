# Evidencias de Funcionamiento - EventCore

Este documento presenta las evidencias técnicas del correcto funcionamiento del sistema EventCore, destacando la fluidez del frontend y su robusta integración con el backend.

## 1. Funcionamiento del Frontend (Vue.js & FullCalendar)

El frontend ha sido modernizado para ofrecer una experiencia reactiva y dinámica.

### Gestión de Eventos Reactiva
- **Evidencia**: El listado de eventos utiliza Vue 3 para filtrar resultados en tiempo real sin recargar la página.
- **Interactividad**: Al escribir en la barra de búsqueda, la tabla se actualiza instantáneamente, demostrando una gestión de estado eficiente.
- **Feedback Visual**: Las barras de progreso de ocupación cambian de color dinámicamente según los datos actuales, proporcionando información visual inmediata al administrador.

### Calendario Interactivo
- **Evidencia**: El módulo de calendario se encuentra totalmente localizado al español y permite la visualización por Mes, Año, Semana, Día y Lista.
- **Acciones**: Permite abrir detalles de eventos y crear nuevos registros mediante modales, manteniendo al usuario dentro del mismo contexto visual.

## 2. Integración Frontend - Backend (Ecosistema API)

La comunicación entre las capas del sistema se realiza de forma asíncrona y segura.

### Consumo de API RESTful
- **Evidencia**: Todas las operaciones de datos en la vista de eventos se realizan mediante llamadas AJAX (Axios) a los endpoints de Laravel (Ej: `GET /api/eventos`).
- **Sincronización**: Al cancelar un evento desde la interfaz de Vue, se envía una petición `DELETE` que el backend procesa, actualizando la base de datos y reflejando el cambio en el frontend sin parpadeos.

### Autenticación y Seguridad Vinculada
- **Evidencia**: El frontend inyecta automáticamente el `api_token` del usuario en las cabeceras de cada petición (`Authorization: Bearer`).
- **Validación**: El backend rechaza cualquier petición del frontend que no incluya un token válido (Error 401), garantizando que solo personal autorizado pueda manipular los datos.

## 3. Integración con Servicios Externos
- **Evidencia**: Se ha integrado un widget de clima que consume una API externa a través de un proxy en el backend de Laravel, mostrando datos meteorológicos de "La Paz" en tiempo real dentro del panel administrativo.

---
*Este documento sirve como evidencia técnica para el informe final de implementación del sistema EventCore.*
