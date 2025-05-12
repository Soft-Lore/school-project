#  Multitenant SaaS API - SCHOOL SERVICE

Este proyecto está diseñado como una API multitenant en Laravel 12, donde cada escuela registrada en el sistema obtiene su propia base de datos aislada.

##  Estructura del proyecto

```
project-root/
├── app/Http/Middleware/EnsureTenantConnection.php
├── app/DTOs/
│   └── Auth/, School/, User/
├── app/Services/
├── app/Actions/
├── database/migrations/tenant/
├── routes/api.php
└── .env.testing
```

---

##  Requisitos

* Docker + Docker Compose
* PHP >= 8.3
* MySQL (contenedor en Docker)

---

##  Cómo levantar el proyecto

```bash
git clone <repo>
cd <project>
cp .env.example .env
docker-compose up -d --build
docker exec -it laravel_app php artisan key:generate
docker exec -it laravel_app php artisan migrate
```

---

##  Registro de escuelas

```http
POST /api/v1/schools/register

Body JSON:
{
  "name": "Colegio Bautista",
  "admin_email": "admin@colegiomv.com",
  "admin_password": "12345678"
}
```

* Crea un registro en la tabla `schools` (base principal)
* Crea una base de datos propia como `colegio-bautista_db`
* Ejecuta las migraciones de `database/migrations/tenant`
* Crea un usuario administrador en la base del colegio

---

## 🧪 Tests

Para ejecutar los tests con MySQL:

1. Asegúrate de tener `.env.testing` con esta config:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

2. Ejecuta:

```bash
docker exec -it laravel_app php artisan test
```

3. Para un test específico:

```bash
docker exec -it laravel_app php artisan test --filter=TenantLoginTest
```

---

##  Comandos útiles

```bash
# Verificar si un colegio está correctamente conectado
php artisan tenant:check colegio-bautista

# Ingresar al contenedor
docker exec -it laravel_app bash

# Correr migraciones de tenant
php artisan migrate --database=tenant --path=database/migrations/tenant --force
```