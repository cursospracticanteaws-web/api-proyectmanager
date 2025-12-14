# API Gestor de Tareas

Sistema de gestión de productividad personal desarrollado con Laravel, que permite organizar proyectos y tareas mediante una API RESTful completa.

## 🚀 Características Principales

- ✅ **Autenticación segura** con Laravel Sanctum
- ✅ **CRUD completo** para Proyectos y Tareas
- ✅ **Validaciones estrictas** con mensajes personalizados en español
- ✅ **API Resources** para transformación consistente de datos
- ✅ **Paginación automática** de resultados
- ✅ **Filtrado avanzado** por múltiples criterios
- ✅ **Relaciones Eloquent** optimizadas
- ✅ **Códigos HTTP apropiados** para cada operación
- ✅ **Seguridad** - Los usuarios solo acceden a sus propios recursos

## 📋 Requisitos del Sistema

- PHP >= 8.1
- Composer
- SQLite (o MySQL/PostgreSQL)
- Extensiones PHP: mbstring, xml, curl, zip, sqlite3

## 🔧 Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd ApiGestorTareas
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos

Edita el archivo `.env` y configura SQLite:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Crear la base de datos

```bash
touch database/database.sqlite
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

### 7. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000/api`

## 📚 Documentación de la API

Consulta la [Documentación Completa de la API](API_DOCUMENTATION.md) para ver todos los endpoints disponibles, ejemplos de uso y códigos de respuesta.

### Endpoints Principales

#### Autenticación
- `POST /api/auth/register` - Registrar nuevo usuario
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/logout` - Cerrar sesión
- `GET /api/auth/me` - Obtener usuario actual

#### Proyectos
- `GET /api/projects` - Listar proyectos
- `POST /api/projects` - Crear proyecto
- `GET /api/projects/{id}` - Ver proyecto
- `PUT /api/projects/{id}` - Actualizar proyecto
- `DELETE /api/projects/{id}` - Eliminar proyecto
- `PUT /api/projects/{id}/archive` - Archivar/desarchivar

#### Tareas
- `GET /api/tasks` - Listar tareas
- `POST /api/tasks` - Crear tarea
- `GET /api/tasks/{id}` - Ver tarea
- `PUT /api/tasks/{id}` - Actualizar tarea
- `DELETE /api/tasks/{id}` - Eliminar tarea
- `PUT /api/tasks/{id}/complete` - Marcar como completada

## 🧪 Pruebas Rápidas

### 1. Registrar un usuario

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Iniciar sesión

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Guarda el token que recibes en la respuesta.

### 3. Crear un proyecto

```bash
curl -X POST http://localhost:8000/api/projects \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TU_TOKEN}" \
  -d '{
    "name": "Mi Primer Proyecto",
    "description": "Descripción del proyecto"
  }'
```

### 4. Crear una tarea

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TU_TOKEN}" \
  -d '{
    "project_id": 1,
    "title": "Mi Primera Tarea",
    "description": "Descripción de la tarea",
    "due_date": "2025-12-20"
  }'
```

### 5. Marcar tarea como completada

```bash
curl -X PUT http://localhost:8000/api/tasks/1/complete \
  -H "Authorization: Bearer {TU_TOKEN}"
```

## 📊 Estructura del Proyecto

```
ApiGestorTareas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ProjectController.php
│   │   │   └── TaskController.php
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   ├── ProjectRequest.php
│   │   │   └── TaskRequest.php
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       ├── ProjectResource.php
│   │       └── TaskResource.php
│   └── Models/
│       ├── User.php
│       ├── Project.php
│       └── Task.php
├── database/
│   └── migrations/
│       ├── 2025_12_04_223525_create_projects_table.php
│       └── 2025_12_04_223528_create_tasks_table.php
├── routes/
│   └── api.php
├── API_DOCUMENTATION.md
└── README.md
```

## 🗄️ Modelos de Datos

### User (Usuario)
- `id`: Identificador único
- `name`: Nombre del usuario
- `email`: Correo electrónico (único)
- `password`: Contraseña hasheada
- `created_at`, `updated_at`: Timestamps

### Project (Proyecto)
- `id`: Identificador único
- `user_id`: ID del usuario propietario
- `name`: Nombre del proyecto
- `description`: Descripción (opcional)
- `is_archived`: Estado de archivado (boolean)
- `created_at`, `updated_at`: Timestamps

### Task (Tarea)
- `id`: Identificador único
- `project_id`: ID del proyecto al que pertenece
- `title`: Título de la tarea
- `description`: Descripción (opcional)
- `due_date`: Fecha de vencimiento (opcional)
- `is_completed`: Estado de completado (boolean)
- `created_at`, `updated_at`: Timestamps

## 🔐 Seguridad

- **Autenticación**: Laravel Sanctum con tokens Bearer
- **Passwords**: Hasheados con bcrypt
- **Autorización**: Middleware `auth:sanctum` en rutas protegidas
- **Validación**: Form Requests con reglas estrictas
- **Permisos**: Los usuarios solo acceden a sus propios recursos

## 🎯 Características Avanzadas Implementadas

### Filtrado de Proyectos
```bash
# Listar solo proyectos archivados
GET /api/projects?is_archived=true

# Listar solo proyectos activos
GET /api/projects?is_archived=false
```

### Filtrado de Tareas
```bash
# Filtrar por proyecto
GET /api/tasks?project_id=1

# Filtrar por estado
GET /api/tasks?is_completed=true

# Filtrar por fecha de vencimiento
GET /api/tasks?due_date=2025-12-20

# Combinar filtros
GET /api/tasks?project_id=1&is_completed=false
```

### Paginación
Todas las listas incluyen paginación automática (15 elementos por página):

```bash
GET /api/projects?page=2
GET /api/tasks?page=3
```

## 🛠️ Tecnologías Utilizadas

- **Framework**: Laravel 10.x
- **Autenticación**: Laravel Sanctum
- **Base de datos**: SQLite (configurable a MySQL/PostgreSQL)
- **ORM**: Eloquent
- **Validación**: Form Requests
- **Transformación de datos**: API Resources
- **PHP**: 8.1+

## 📝 Notas de Desarrollo

### Relaciones Eloquent

- User → hasMany → Projects
- Project → belongsTo → User
- Project → hasMany → Tasks
- Task → belongsTo → Project

### Validaciones Personalizadas

Todos los Form Requests incluyen:
- Reglas de validación estrictas
- Mensajes de error en español
- Soporte para JSON/AJAX

### API Resources

Transformación consistente de datos con:
- Carga condicional de relaciones (`whenLoaded`)
- Contadores condicionales (`whenCounted`)
- Formato ISO para fechas

## 🚦 Códigos de Estado HTTP

| Código | Uso |
|--------|-----|
| 200 | OK - Operación exitosa |
| 201 | Created - Recurso creado |
| 400 | Bad Request - Error de validación |
| 401 | Unauthorized - No autenticado |
| 403 | Forbidden - Sin permisos |
| 404 | Not Found - Recurso no encontrado |
| 500 | Internal Server Error - Error del servidor |

## 🤝 Contribuciones

Este proyecto fue desarrollado como parte del curso de desarrollo web, siguiendo las mejores prácticas de Laravel y arquitectura RESTful.

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👨‍💻 Autor

Desarrollado como proyecto académico para la gestión de tareas personales.

---

**¿Necesitas ayuda?** Consulta la [Documentación Completa de la API](API_DOCUMENTATION.md) para más detalles.
