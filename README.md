# Entorno de desarrollo 

##  Estructura del Proyecto

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
└── (archivos Laravel)
```

---

##  Requisitos Previos

- Docker instalado: [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)
- Docker Compose (viene con Docker Desktop en Windows/Mac)

---

##  Pasos para levantar el proyecto

### 1. Clonar o iniciar el proyecto

```bash
git clone <url-del-repo>
cd <nombre-del-proyecto>
```

O crea el proyecto con Composer:

```bash
composer create-project laravel/laravel . "^12.0"
```

### 2. Configurar el archivo `.env`

```bash
cp .env.example .env
```

Y asegúrate de que la configuración de base de datos sea:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=secret
```

### 3. Construir e iniciar los contenedores

```bash
docker-compose up -d --build
```

Esto levanta:
- `laravel_app`: Contenedor con PHP y Laravel
- `laravel_nginx`: Servidor web Nginx
- `laravel_mysql`: Base de datos MySQL

### 4. Generar la clave de la app

```bash
docker exec -it laravel_app php artisan key:generate
```

---

##  Acceder a la API

- En el navegador: [http://localhost:8000](http://localhost:8000)

---

##  Acceso a la base de datos

Usando herramientas como **MySQL Workbench** o **DBeaver**:

| Parámetro     | Valor     |
|---------------|-----------|
| Hostname      | 127.0.0.1 |
| Puerto        | 3307      |
| Usuario       | user      |
| Contraseña    | secret    |
| Base de datos | laravel   |

---

##  Comandos útiles para desarrollo

```bash
# Ver contenedores en ejecución
docker ps

# Entrar al contenedor Laravel
docker exec -it laravel_app bash

# Ejecutar migraciones
docker exec -it laravel_app php artisan migrate

# Ver logs de MySQL
docker logs laravel_mysql

# Apagar y eliminar los contenedores
docker-compose down
```
