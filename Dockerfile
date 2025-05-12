FROM php:8.3-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libicu-dev \
    g++ \
    libxslt-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia los archivos de tu proyecto al directorio de trabajo
COPY . /var/www/html/

# Instala las dependencias
RUN composer install --no-dev --optimize-autoloader

# Ejecuta las migraciones de Doctrine
RUN php bin/console doctrine:migrations:migrate --no-interaction

# Limpia la caché de Symfony (opcional, puede hacerse en el release phase también)
RUN php bin/console cache:clear --no-warmup --env=prod