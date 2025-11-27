# ----------------------------------------------------------------------------------
# ETAPA 1: BUILD
# ----------------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS builder

WORKDIR /app

RUN apk add --no-cache \
    git \
    curl \
    unzip \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    linux-headers \
    nodejs \
    npm \
    autoconf \
    g++ \
    make

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        zip \
        exif \
        pcntl \
        gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN npm ci && npm run build

# ----------------------------------------------------------------------------------
# ETAPA 2: PRODUCCIÓN
# ----------------------------------------------------------------------------------
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    postgresql-libs \
    icu-libs \
    zlib

RUN set -ex \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        make \
        zlib-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        zip \
        exif \
        pcntl \
        gd \
    && apk del .build-deps

RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache

# --- CAMBIO IMPORTANTE: NO COPIAMOS EL .ENV ---
# Eliminé la línea COPY .env.example .env para evitar que Laravel tome defaults incorrectos.

COPY --from=builder /app /var/www/html

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY nginx/supervisor.conf /etc/supervisor/conf.d/supervisord.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
