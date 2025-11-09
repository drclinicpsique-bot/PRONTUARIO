FROM php:8.2-cli

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar arquivos
COPY . .

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader

# Criar diretórios com permissões
RUN mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# Script de inicialização com migrations
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080
