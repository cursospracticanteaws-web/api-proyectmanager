# Documentación API Gestor de Tareas

## Descripción General

API RESTful desarrollada en Laravel para la gestión personal de proyectos y tareas. Implementa autenticación mediante Laravel Sanctum y proporciona endpoints completos para operaciones CRUD sobre proyectos y tareas.

## URL Base

```
http://localhost:8000/api
```

## Autenticación

La API utiliza **Laravel Sanctum** para autenticación basada en tokens. Todos los endpoints (excepto registro y login) requieren el header:

```
Authorization: Bearer {token}
```

---

## Endpoints de Autenticación

### 1. Registrar Usuario

**Endpoint:** `POST /auth/register`

**Descripción:** Crea un nuevo usuario en el sistema.

**Body (JSON):**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validaciones:**
- `name`: requerido, string, máximo 255 caracteres
- `email`: requerido, email válido, único, máximo 255 caracteres
- `password`: requerido, string, mínimo 8 caracteres, debe coincidir con confirmación

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "created_at": "2025-12-04T22:39:00.000000Z",
      "updated_at": "2025-12-04T22:39:00.000000Z"
    },
    "token": "1|Gl7nkjEJis60Bsqg5eRoCTUdebjA3eqctr9UAlPi41abeafa"
  }
}
```

---

### 2. Iniciar Sesión

**Endpoint:** `POST /auth/login`

**Descripción:** Autentica un usuario y retorna un token de acceso.

**Body (JSON):**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Validaciones:**
- `email`: requerido, email válido
- `password`: requerido, string

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Inicio de sesión exitoso",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "projects": [],
      "created_at": "2025-12-04T22:39:00.000000Z",
      "updated_at": "2025-12-04T22:39:00.000000Z"
    },
    "token": "2|Nll2pPxWLvbg9Ki3en7pGq1UifeXDflUwoeoU4ySa7f3de90"
  }
}
```

**Respuesta Error (401):**
```json
{
  "success": false,
  "message": "Las credenciales proporcionadas son incorrectas."
}
```

---

### 3. Cerrar Sesión

**Endpoint:** `POST /auth/logout`

**Descripción:** Invalida todos los tokens del usuario autenticado.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

### 4. Obtener Usuario Actual

**Endpoint:** `GET /auth/me`

**Descripción:** Retorna la información del usuario autenticado con sus proyectos.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "projects": [...],
      "created_at": "2025-12-04T22:39:00.000000Z",
      "updated_at": "2025-12-04T22:39:00.000000Z"
    }
  }
}
```

---

## Endpoints de Proyectos

### 5. Listar Proyectos

**Endpoint:** `GET /projects`

**Descripción:** Obtiene todos los proyectos del usuario autenticado con paginación.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (opcionales):**
- `is_archived`: `true` o `false` - Filtra proyectos archivados o activos
- `page`: número de página (paginación)

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "projects": [
      {
        "id": 1,
        "name": "Proyecto de Prueba",
        "description": "Este es un proyecto de prueba",
        "is_archived": false,
        "tasks_count": 5,
        "created_at": "2025-12-04T22:39:07.000000Z",
        "updated_at": "2025-12-04T22:39:07.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 1,
      "last_page": 1,
      "from": 1,
      "to": 1
    }
  }
}
```

---

### 6. Ver Proyecto

**Endpoint:** `GET /projects/{id}`

**Descripción:** Obtiene los detalles de un proyecto específico con sus tareas.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Proyecto de Prueba",
    "description": "Este es un proyecto de prueba",
    "is_archived": false,
    "tasks_count": 1,
    "tasks": [
      {
        "id": 1,
        "project_id": 1,
        "title": "Tarea de ejemplo",
        "description": "Esta es una tarea de prueba",
        "due_date": "2025-12-15",
        "is_completed": true,
        "created_at": "2025-12-04T22:39:11.000000Z",
        "updated_at": "2025-12-04T22:39:14.000000Z"
      }
    ],
    "created_at": "2025-12-04T22:39:07.000000Z",
    "updated_at": "2025-12-04T22:39:07.000000Z"
  }
}
```

**Respuesta Error (404):**
```json
{
  "success": false,
  "message": "Proyecto no encontrado"
}
```

---

### 7. Crear Proyecto

**Endpoint:** `POST /projects`

**Descripción:** Crea un nuevo proyecto para el usuario autenticado.

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "name": "Mi Nuevo Proyecto",
  "description": "Descripción del proyecto",
  "is_archived": false
}
```

**Validaciones:**
- `name`: requerido, string, máximo 255 caracteres
- `description`: opcional, string
- `is_archived`: opcional, booleano (default: false)

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Proyecto creado exitosamente",
  "data": {
    "id": 2,
    "name": "Mi Nuevo Proyecto",
    "description": "Descripción del proyecto",
    "is_archived": false,
    "tasks_count": 0,
    "created_at": "2025-12-04T22:45:00.000000Z",
    "updated_at": "2025-12-04T22:45:00.000000Z"
  }
}
```

---

### 8. Actualizar Proyecto

**Endpoint:** `PUT /projects/{id}`

**Descripción:** Actualiza un proyecto existente.

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "name": "Proyecto Actualizado",
  "description": "Nueva descripción",
  "is_archived": false
}
```

**Validaciones:**
- `name`: requerido, string, máximo 255 caracteres
- `description`: opcional, string
- `is_archived`: opcional, booleano

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Proyecto actualizado exitosamente",
  "data": {
    "id": 1,
    "name": "Proyecto Actualizado",
    "description": "Nueva descripción",
    "is_archived": false,
    "tasks_count": 1,
    "created_at": "2025-12-04T22:39:07.000000Z",
    "updated_at": "2025-12-04T22:50:00.000000Z"
  }
}
```

---

### 9. Eliminar Proyecto

**Endpoint:** `DELETE /projects/{id}`

**Descripción:** Elimina un proyecto y todas sus tareas asociadas.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Proyecto eliminado exitosamente"
}
```

---

### 10. Archivar/Desarchivar Proyecto

**Endpoint:** `PUT /projects/{id}/archive`

**Descripción:** Alterna el estado de archivado de un proyecto.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Proyecto archivado exitosamente",
  "data": {
    "id": 1,
    "name": "Proyecto de Prueba",
    "description": "Este es un proyecto de prueba",
    "is_archived": true,
    "tasks_count": 1,
    "created_at": "2025-12-04T22:39:07.000000Z",
    "updated_at": "2025-12-04T22:55:00.000000Z"
  }
}
```

---

## Endpoints de Tareas

### 11. Listar Tareas

**Endpoint:** `GET /tasks`

**Descripción:** Obtiene todas las tareas de los proyectos del usuario con paginación.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (opcionales):**
- `project_id`: ID del proyecto - Filtra tareas por proyecto
- `is_completed`: `true` o `false` - Filtra tareas completadas o pendientes
- `due_date`: fecha en formato YYYY-MM-DD - Filtra por fecha de vencimiento
- `page`: número de página (paginación)

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "tasks": [
      {
        "id": 1,
        "project_id": 1,
        "project": {
          "id": 1,
          "name": "Proyecto de Prueba",
          "description": "Este es un proyecto de prueba",
          "is_archived": false,
          "created_at": "2025-12-04T22:39:07.000000Z",
          "updated_at": "2025-12-04T22:39:07.000000Z"
        },
        "title": "Tarea de ejemplo",
        "description": "Esta es una tarea de prueba",
        "due_date": "2025-12-15",
        "is_completed": false,
        "created_at": "2025-12-04T22:39:11.000000Z",
        "updated_at": "2025-12-04T22:39:11.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 1,
      "last_page": 1,
      "from": 1,
      "to": 1
    }
  }
}
```

---

### 12. Ver Tarea

**Endpoint:** `GET /tasks/{id}`

**Descripción:** Obtiene los detalles de una tarea específica.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "project_id": 1,
    "project": {
      "id": 1,
      "name": "Proyecto de Prueba",
      "description": "Este es un proyecto de prueba",
      "is_archived": false,
      "created_at": "2025-12-04T22:39:07.000000Z",
      "updated_at": "2025-12-04T22:39:07.000000Z"
    },
    "title": "Tarea de ejemplo",
    "description": "Esta es una tarea de prueba",
    "due_date": "2025-12-15",
    "is_completed": false,
    "created_at": "2025-12-04T22:39:11.000000Z",
    "updated_at": "2025-12-04T22:39:11.000000Z"
  }
}
```

**Respuesta Error (404):**
```json
{
  "success": false,
  "message": "Tarea no encontrada"
}
```

---

### 13. Crear Tarea

**Endpoint:** `POST /tasks`

**Descripción:** Crea una nueva tarea asociada a un proyecto.

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "project_id": 1,
  "title": "Nueva tarea",
  "description": "Descripción de la tarea",
  "due_date": "2025-12-20",
  "is_completed": false
}
```

**Validaciones:**
- `project_id`: requerido, debe existir y pertenecer al usuario
- `title`: requerido, string, máximo 255 caracteres
- `description`: opcional, string
- `due_date`: opcional, fecha válida (YYYY-MM-DD)
- `is_completed`: opcional, booleano (default: false)

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Tarea creada exitosamente",
  "data": {
    "id": 2,
    "project_id": 1,
    "project": {
      "id": 1,
      "name": "Proyecto de Prueba",
      "description": "Este es un proyecto de prueba",
      "is_archived": false,
      "created_at": "2025-12-04T22:39:07.000000Z",
      "updated_at": "2025-12-04T22:39:07.000000Z"
    },
    "title": "Nueva tarea",
    "description": "Descripción de la tarea",
    "due_date": "2025-12-20",
    "is_completed": false,
    "created_at": "2025-12-04T23:00:00.000000Z",
    "updated_at": "2025-12-04T23:00:00.000000Z"
  }
}
```

**Respuesta Error (403):**
```json
{
  "success": false,
  "message": "No tienes permiso para agregar tareas a este proyecto"
}
```

---

### 14. Actualizar Tarea

**Endpoint:** `PUT /tasks/{id}`

**Descripción:** Actualiza una tarea existente.

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "project_id": 1,
  "title": "Tarea actualizada",
  "description": "Nueva descripción",
  "due_date": "2025-12-25",
  "is_completed": true
}
```

**Validaciones:**
- `project_id`: requerido, debe existir y pertenecer al usuario
- `title`: requerido, string, máximo 255 caracteres
- `description`: opcional, string
- `due_date`: opcional, fecha válida
- `is_completed`: opcional, booleano

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Tarea actualizada exitosamente",
  "data": {
    "id": 1,
    "project_id": 1,
    "project": {...},
    "title": "Tarea actualizada",
    "description": "Nueva descripción",
    "due_date": "2025-12-25",
    "is_completed": true,
    "created_at": "2025-12-04T22:39:11.000000Z",
    "updated_at": "2025-12-04T23:05:00.000000Z"
  }
}
```

---

### 15. Eliminar Tarea

**Endpoint:** `DELETE /tasks/{id}`

**Descripción:** Elimina una tarea.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Tarea eliminada exitosamente"
}
```

---

### 16. Marcar Tarea como Completada/Pendiente

**Endpoint:** `PUT /tasks/{id}/complete`

**Descripción:** Alterna el estado de completado de una tarea.

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Tarea marcada como completada",
  "data": {
    "id": 1,
    "project_id": 1,
    "project": {...},
    "title": "Tarea de ejemplo",
    "description": "Esta es una tarea de prueba",
    "due_date": "2025-12-15",
    "is_completed": true,
    "created_at": "2025-12-04T22:39:11.000000Z",
    "updated_at": "2025-12-04T23:10:00.000000Z"
  }
}
```

---

## Códigos de Estado HTTP

La API utiliza los siguientes códigos de estado:

| Código | Descripción |
|--------|-------------|
| 200 | OK - Solicitud exitosa |
| 201 | Created - Recurso creado exitosamente |
| 400 | Bad Request - Error en la validación de datos |
| 401 | Unauthorized - Credenciales inválidas o token ausente |
| 403 | Forbidden - No tiene permisos para realizar la acción |
| 404 | Not Found - Recurso no encontrado |
| 500 | Internal Server Error - Error del servidor |

---

## Estructura de Respuestas

Todas las respuestas de la API siguen un formato consistente:

### Respuesta Exitosa
```json
{
  "success": true,
  "message": "Mensaje descriptivo",
  "data": {
    // Datos de la respuesta
  }
}
```

### Respuesta de Error
```json
{
  "success": false,
  "message": "Descripción del error"
}
```

### Errores de Validación
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "El campo es obligatorio."
    ]
  }
}
```

---

## Ejemplos de Uso con cURL

### Registrar un usuario
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Iniciar sesión
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

### Crear un proyecto
```bash
curl -X POST http://localhost:8000/api/projects \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "name": "Mi Proyecto",
    "description": "Descripción del proyecto"
  }'
```

### Crear una tarea
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "project_id": 1,
    "title": "Mi Tarea",
    "description": "Descripción de la tarea",
    "due_date": "2025-12-20"
  }'
```

### Marcar tarea como completada
```bash
curl -X PUT http://localhost:8000/api/tasks/1/complete \
  -H "Authorization: Bearer {token}"
```

### Listar proyectos
```bash
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer {token}"
```

### Filtrar tareas por proyecto
```bash
curl -X GET "http://localhost:8000/api/tasks?project_id=1" \
  -H "Authorization: Bearer {token}"
```

---

## Modelos de Datos

### User (Usuario)
```
- id: integer
- name: string
- email: string (único)
- password: string (hasheado)
- created_at: timestamp
- updated_at: timestamp
```

### Project (Proyecto)
```
- id: integer
- user_id: integer (FK -> users)
- name: string
- description: text (nullable)
- is_archived: boolean (default: false)
- created_at: timestamp
- updated_at: timestamp
```

### Task (Tarea)
```
- id: integer
- project_id: integer (FK -> projects)
- title: string
- description: text (nullable)
- due_date: date (nullable)
- is_completed: boolean (default: false)
- created_at: timestamp
- updated_at: timestamp
```

---

## Relaciones

- Un **Usuario** tiene muchos **Proyectos** (1:N)
- Un **Proyecto** pertenece a un **Usuario** (N:1)
- Un **Proyecto** tiene muchas **Tareas** (1:N)
- Una **Tarea** pertenece a un **Proyecto** (N:1)

---

## Características Implementadas

✅ **Autenticación con Laravel Sanctum**
- Registro de usuarios
- Login con tokens
- Logout (invalidación de tokens)
- Protección de rutas

✅ **CRUD Completo de Proyectos**
- Crear, leer, actualizar y eliminar proyectos
- Archivar/desarchivar proyectos
- Filtrado por estado de archivado
- Contador de tareas por proyecto

✅ **CRUD Completo de Tareas**
- Crear, leer, actualizar y eliminar tareas
- Marcar como completada/pendiente
- Filtrado por proyecto, estado y fecha
- Asociación con proyectos

✅ **Validaciones Estrictas**
- Form Requests personalizados
- Mensajes de error en español
- Validación de permisos (usuario solo accede a sus recursos)

✅ **API Resources**
- Transformación consistente de datos
- Carga condicional de relaciones
- Formato ISO para fechas

✅ **Paginación**
- 15 elementos por página
- Metadata de paginación incluida

✅ **Códigos HTTP Apropiados**
- 200, 201, 400, 401, 403, 404, 500

---

## Instalación y Configuración

### Requisitos
- PHP >= 8.1
- Composer
- SQLite (o MySQL/PostgreSQL)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd ApiGestorTareas
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar el archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos**
```
DB_CONNECTION=sqlite
```

5. **Crear la base de datos**
```bash
touch database/database.sqlite
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Iniciar el servidor**
```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000/api`

---

## Notas de Seguridad

- Todos los passwords se hashean con bcrypt
- Los tokens de autenticación son únicos y seguros
- Las rutas protegidas requieren autenticación válida
- Los usuarios solo pueden acceder a sus propios recursos
- Las validaciones previenen inyección de datos maliciosos

---

## Soporte

Para reportar problemas o solicitar nuevas funcionalidades, por favor contacta al equipo de desarrollo.

---

**Versión:** 1.0.0  
**Fecha:** Diciembre 2025  
**Framework:** Laravel 10.x  
**Autenticación:** Laravel Sanctum
