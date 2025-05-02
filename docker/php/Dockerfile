FROM php:8.3-fpm

# Instala dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev

# Extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl gd

# Instalacion de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setea el directorio de trabajo
WORKDIR /var/www
