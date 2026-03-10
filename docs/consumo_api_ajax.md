# Consumo de API mediante AJAX/Axios - EventCore

En esta sección se explica técnicamente cómo el frontend del sistema EventCore interactúa con la API de Laravel utilizando tecnologías de comunicación asíncrona.

## 1. Tecnologías Utilizadas

Para la comunicación entre el cliente y el servidor, el sistema emplea:
- **AJAX (Asynchronous JavaScript and XML)**: Técnica que permite actualizar partes de una página web sin recargarla por completo.
- **Axios**: Una librería cliente HTTP basada en promesas para el navegador y node.js. Es la herramienta principal en EventCore para el consumo de la API debido a su facilidad de uso y manejo automático de datos JSON.

### Ejemplos de Peticiones

#### Petición GET (Obtención de Datos)
Se utiliza para recuperar información del servidor. Axios maneja la respuesta como un objeto JSON automáticamente.
```javascript
axios.get('/api/eventos')
  .then(response => {
    this.eventos = response.data.data;
  })
  .catch(error => console.error(error));
```

#### Petición POST (Envío de Datos)
Se utiliza para crear nuevos registros o realizar acciones como el Login. Los datos se envían en el cuerpo de la petición.
```javascript
axios.post('/api/eventos', {
    titulo: 'Nuevo Evento',
    fecha_inicio: '2026-04-10 10:00:00',
    // ... otros campos
})
.then(response => console.log('Creado!', response))
.catch(error => alert('Error al crear'));
```

## 3. Autenticación mediante Token

Para las rutas protegidas, el sistema requiere un **Bearer Token**. Este debe enviarse en las cabeceras de cada petición AJAX.

### Configuración del Header de Autorización
```javascript
const token = 'TU_TOKEN_GENERADO'; // Obtenido tras el login

axios.get('/api/eventos', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
})
.then(response => {
    this.eventos = response.data.data;
});
```

## 4. Flujo de Datos

1. **Petición**: El frontend inicia una llamada AJAX (GET, POST, PUT, DELETE).
2. **Procesamiento**: Laravel recibe la petición, procesa la lógica en los controladores y devuelve una respuesta estructurada en formato JSON.
3. **Respuesta**: Axios intercepta la respuesta y la convierte automáticamente en un objeto JavaScript accesible.
4. **Actualización Reactiva**: Vue.js detecta el cambio en los datos y actualiza la interfaz de usuario de forma instantánea sin parpadeos ni recargas.

## 4. Ventajas del Enfoque AJAX en EventCore

- **Interactividad**: Búsquedas y filtros instantáneos.
- **Eficiencia**: Solo se transfieren los datos necesarios (JSON), reduciendo el consumo de ancho de banda.
- **Experiencia de Usuario (UX)**: El sistema se siente como una aplicación nativa, rápida y fluida.

---
*Documentación complementaria para el informe técnico, detallando la capa de comunicación cliente-servidor.*
