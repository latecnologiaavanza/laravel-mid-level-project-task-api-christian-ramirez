---

````md
# 🧪 Laravel Mid-Level Project & Task API

Este proyecto es parte de una prueba técnica para desarrollador backend Laravel.  
Se trata de una API RESTful para gestionar proyectos y tareas con relaciones, filtros dinámicos, validaciones estrictas, auditoría con `owen-it/laravel-auditing`, documentación con L5-Swagger y monitoreo con Laravel Telescope.

---

## ✅ Requisitos del sistema

- PHP >= 8.1
- Composer >= 2.x
- Laravel 10.x
- Extensión `sqlite3` habilitada
- Node.js y npm (opcional, para UI de Telescope)

---

## ⚙️ Instalación paso a paso

1. **Clonar el repositorio**

```bash
git clone https://github.com/latecnologiaavanza/laravel-mid-level-project-task-api-christian-ramirez.git
````

2. **Instalar dependencias PHP**

```bash
composer install
```

3. **Copiar el archivo de entorno**

```bash
cp .env.example .env
```

4. **Crear base de datos SQLite**

```bash
touch database/database.sqlite
```

5. **Editar `.env` y configurar SQLite**

Abre el archivo `.env` y configura la base de datos así:

```env
DB_CONNECTION=sqlite
DB_DATABASE=${PWD}/database/database.sqlite
```

(O reemplaza la ruta si estás en Windows)

6. **Generar la APP\_KEY**

```bash
php artisan key:generate
```

7. **Ejecutar migraciones**

```bash
php artisan migrate
```

8. **Levantar el servidor**

```bash
php artisan serve
```

---

## 🧾 Cómo levantar Swagger (Documentación de la API)

1. Instalar L5-Swagger (ya incluido en `composer.json`)
2. Asegúrate de tener el archivo `app/Swagger/SwaggerInfo.php` con la anotación `@OA\Info`.
3. Generar documentación:

```bash
php artisan l5-swagger:generate
```

4. Acceder a la documentación:

```
http://localhost:8000/api/documentation
```

---

## 🔍 Cómo ver Laravel Telescope

1. Laravel Telescope ya está instalado y registrado en `AppServiceProvider`.
2. Para acceder a Telescope, solo abre:

```
http://localhost:8000/telescope
```

> Si estás en producción, asegúrate de configurar correctamente `TelescopeServiceProvider`.

---

## 🧪 Cómo probar filtros dinámicos

### Proyectos

* Filtrar por status:

  ```
  GET /api/projects?status=active
  ```

* Filtrar por nombre parcial:

  ```
  GET /api/projects?name=CRM
  ```

* Filtrar por fecha de creación:

  ```
  GET /api/projects?date_from=2024-01-01&date_to=2024-12-31
  ```

### Tareas

* Filtrar por prioridad, estado, fecha o proyecto:

  ```
  GET /api/tasks?status=pending&priority=high&project_id=uuid&due_date=2025-01-01
  ```

---

## 📋 Cómo ver logs de auditoría

Este proyecto usa [`owen-it/laravel-auditing`](https://github.com/OwenIt/laravel-auditing).

1. Cada vez que se crea, actualiza o elimina un registro, se guarda automáticamente en la tabla `audits`.
2. Puedes consultar los registros manualmente desde Tinker:

```bash
php artisan tinker
>>> \OwenIt\Auditing\Models\Audit::latest()->get();
```

---

