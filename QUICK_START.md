# Guía de Inicio Rápido - API Gestor de Tareas

Esta guía te ayudará a poner en marcha la API en menos de 5 minutos.

## ⚡ Instalación Rápida

```bash
# 1. Navegar al directorio del proyecto
cd ApiGestorTareas

# 2. Instalar dependencias (si no lo has hecho)
composer install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 4. Crear la base de datos
touch database/database.sqlite

# 5. Ejecutar migraciones
php artisan migrate

# 6. Iniciar el servidor
php artisan serve
```

## 🎯 Prueba Completa en 5 Pasos

### Paso 1: Registrar un Usuario

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario Demo",
    "email": "demo@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Resultado esperado:**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {...},
    "token": "1|abc123..."
  }
}
```

**👉 IMPORTANTE:** Guarda el token que recibes, lo necesitarás para los siguientes pasos.

---

### Paso 2: Crear un Proyecto

Reemplaza `{TU_TOKEN}` con el token que recibiste en el paso anterior.

```bash
curl -X POST http://localhost:8000/api/projects \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TU_TOKEN}" \
  -d '{
    "name": "Proyecto Demo",
    "description": "Mi primer proyecto de prueba"
  }'
```

**Resultado esperado:**
```json
{
  "success": true,
  "message": "Proyecto creado exitosamente",
  "data": {
    "id": 1,
    "name": "Proyecto Demo",
    "description": "Mi primer proyecto de prueba",
    "is_archived": false,
    "tasks_count": 0,
    ...
  }
}
```

---

### Paso 3: Crear una Tarea

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TU_TOKEN}" \
  -d '{
    "project_id": 1,
    "title": "Tarea de Prueba",
    "description": "Esta es mi primera tarea",
    "due_date": "2025-12-20"
  }'
```

**Resultado esperado:**
```json
{
  "success": true,
  "message": "Tarea creada exitosamente",
  "data": {
    "id": 1,
    "project_id": 1,
    "title": "Tarea de Prueba",
    "description": "Esta es mi primera tarea",
    "due_date": "2025-12-20",
    "is_completed": false,
    ...
  }
}
```

---

### Paso 4: Listar Proyectos con sus Tareas

```bash
curl -X GET http://localhost:8000/api/projects/1 \
  -H "Authorization: Bearer {TU_TOKEN}"
```

**Resultado esperado:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Proyecto Demo",
    "description": "Mi primer proyecto de prueba",
    "is_archived": false,
    "tasks_count": 1,
    "tasks": [
      {
        "id": 1,
        "title": "Tarea de Prueba",
        "is_completed": false,
        ...
      }
    ],
    ...
  }
}
```

---

### Paso 5: Marcar Tarea como Completada

```bash
curl -X PUT http://localhost:8000/api/tasks/1/complete \
  -H "Authorization: Bearer {TU_TOKEN}"
```

**Resultado esperado:**
```json
{
  "success": true,
  "message": "Tarea marcada como completada",
  "data": {
    "id": 1,
    "title": "Tarea de Prueba",
    "is_completed": true,
    ...
  }
}
```

---

## 🎉 ¡Felicidades!

Has completado exitosamente la configuración y prueba de la API. Ahora puedes:

1. **Explorar más endpoints** - Consulta [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
2. **Crear más proyectos y tareas**
3. **Probar los filtros** - Por ejemplo: `GET /api/tasks?is_completed=true`
4. **Archivar proyectos** - `PUT /api/projects/1/archive`

## 📋 Comandos Útiles

### Ver todas las rutas disponibles
```bash
php artisan route:list --path=api
```

### Limpiar la base de datos y empezar de nuevo
```bash
php artisan migrate:fresh
```

### Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log
```

### Ejecutar el servidor en un puerto diferente
```bash
php artisan serve --port=8080
```

## 🔧 Solución de Problemas

### Error: "Base de datos no encontrada"
```bash
# Asegúrate de que el archivo existe
touch database/database.sqlite
php artisan migrate
```

### Error: "Token inválido"
```bash
# Genera un nuevo token iniciando sesión
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"demo@example.com","password":"password123"}'
```

### Error: "Proyecto no encontrado"
```bash
# Verifica que el proyecto pertenece a tu usuario
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer {TU_TOKEN}"
```

## 📚 Próximos Pasos

1. Lee la [Documentación Completa](API_DOCUMENTATION.md) para conocer todos los endpoints
2. Prueba los filtros avanzados de tareas
3. Implementa el frontend consumiendo esta API
4. Explora las validaciones y manejo de errores

---

**¿Tienes preguntas?** Consulta la documentación completa o revisa los archivos de código fuente.
