# EventCore - Sistema de Gestión de Eventos

EventCore es una plataforma robusta construida con Laravel diseñada para la gestión integral de eventos, participantes e inscripciones. Ofrece una interfaz intuitiva y una API potente para administrar categorías, estados de eventos y roles de usuario.

## 🚀 Características Principales

- **Gestión de Eventos:** Creación, edición y monitoreo de eventos.
- **Control de Participantes:** Registro y administración de asistentes.
- **Inscripciones Inteligentes:** Seguimiento del estado de las inscripciones.
- **Administración de Sedes:** Gestión de ubicaciones físicas para los eventos.
- **Categorización:** Organización de eventos por categorías personalizables.
- **Seguridad:** Sistema de roles (Admin, Participante, etc.) para control de acceso.

## 🛠️ Requisitos del Sistema

- **PHP:** ^8.2
- **Composer**
- **MySQL / PostgreSQL**
- **Node.js & NPM**

## 📦 Guía de Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/eventcore.git
   cd eventcore
   ```

2. **Instala las dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configura el entorno:**
   Copia el archivo de ejemplo y configura tus credenciales de base de datos en el archivo `.env`.
   ```bash
   cp .env.example .env
   ```

4. **Genera la clave de la aplicación:**
   ```bash
   php artisan key:generate
   ```

5. **Configura la Base de Datos:**
   Crea una base de datos y ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```
   *(Opcional) Si deseas datos de prueba:*
   ```bash
   php artisan db:seed
   ```

6. **Instala y compila las dependencias de Frontend:**
   ```bash
   npm install
   ```

7. **Configuración Rápida (Shortcut):**
   El proyecto incluye un comando de configuración todo-en-uno definido en `composer.json`:
   ```bash
   composer run setup
   ```

## ⌨️ Comandos Disponibles

### Desarrollo
- `npm run dev` - Inicia el servidor de desarrollo de Vite.
- `php artisan serve` - Inicia el servidor de desarrollo de Laravel.
- `composer run dev` - Inicia simultáneamente servidor, cola de trabajos y Vite.

### Base de Datos
- `php artisan migrate` - Ejecuta las migraciones.
- `php artisan migrate:rollback` - Revierte la última migración.
- `php artisan db:seed` - Ejecuta los seeders para datos iniciales.

### Test y Calidad
- `composer run test` - Ejecuta las pruebas unitarias y de integración.
- `php artisan pint` - Estiliza el código según los estándares (Laravel Pint).



