##  Entorno de desarrollo

###  Estructura del proyecto principal

```
project-root/
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   ├── nginx/
│   │   └── default.conf
├── .env
├── docker-compose.yml
├── README.md
├── .github/workflows/phpunit.yml
├── app/
│   ├── Actions/
│   │   └── User/
│   ├── DTOs/
│   │   └── User/
│   ├── Repositories/
│   │   ├── Contracts/
│   │   └── Eloquent/
│   ├── Services/
│   └── Http/Controllers/Api/
└── (archivos Laravel)
```

---

##  Requisitos previos

- Docker: https://docs.docker.com/get-docker/
- Docker Compose (incluido en Docker Desktop)

---

##  Pasos para levantar el proyecto

### 1. Clonar el repositorio o iniciar el proyecto

```bash
git clone <url-del-repo>
cd <nombre-del-proyecto>
```

O crear con Composer:

```bash
composer create-project laravel/laravel . "^12.0"
```

### 2. Configurar el archivo `.env`

```bash
cp .env.example .env
```

Variables esenciales:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=secret
```

### 3. Construir e iniciar contenedores

```bash
docker-compose up -d --build
```

Contenedores levantados:
- `laravel_app`: PHP y Laravel
- `laravel_nginx`: Servidor web Nginx
- `laravel_mysql`: Base de datos MySQL

### 4. Generar la clave de aplicación

```bash
docker exec -it laravel_app php artisan key:generate
```

---

##  Acceder a la API

- Navegador: http://localhost:8000
- Documentación: http://localhost:8000/api/documentation

---

##  Pruebas y CI

### Tests locales

```bash
docker exec -it laravel_app php artisan test
```

### GitHub Actions (CI)
- Ejecuta `php artisan test` automáticamente al hacer PR a `develop` o `main`.
- Utiliza archivo `.github/workflows/phpunit.yml`

### `.env.testing` de ejemplo

```env
APP_ENV=testing
APP_KEY=base64:...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=testing
DB_USERNAME=user
DB_PASSWORD=secret
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

##  Autenticación

- Laravel Sanctum con tokens Bearer

### Headers necesarios para endpoints protegidos:

```
Authorization: Bearer <token>
```

---

## Documentación Swagger

- UI interactiva: http://localhost:8000/api/documentation
- Generar documentación manualmente:

```bash
docker exec -it laravel_app php artisan l5-swagger:generate
```

---

## Funcionalidades actuales

| Endpoint                         | Método | Función                                     |
|----------------------------------|--------|---------------------------------------------|
| `/api/v1/login`                 | POST   | Iniciar sesión y recibir token              |
| `/api/v1/logout`                | POST   | Cerrar sesión y revocar token               |
| `/api/v1/me`                    | GET    | Obtener usuario autenticado                 |
| `/api/v1/users`                | GET    | Listar o filtrar usuarios (`?search=`)      |
| `/api/v1/users/register`        | POST   | Crear nuevo usuario                         |
| `/api/v1/users/update`          | PUT    | Actualizar información del usuario          |
| `/api/v1/users/delete`          | DELETE | Eliminar un usuario                         |
| `/api/v1/users/change-password` | POST   | Cambiar contraseña del usuario autenticado  |

---

## Comandos útiles

```bash
# Ver contenedores activos
docker ps

# Entrar al contenedor Laravel
docker exec -it laravel_app bash

# Ejecutar migraciones
docker exec -it laravel_app php artisan migrate

# Apagar y eliminar contenedores
docker-compose down
```
