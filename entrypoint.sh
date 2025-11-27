#!/bin/sh

# Salir si ocurre un error
set -e

# Ejecutar migraciones (Importante para producción)
echo "Ejecutando migraciones..."
php artisan migrate --force

# Caching de configuración, rutas y vistas para optimizar velocidad
echo "Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Iniciar PHP-FPM en segundo plano
echo "Iniciando PHP-FPM..."
php-fpm -D

# Iniciar Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g "daemon off;"
