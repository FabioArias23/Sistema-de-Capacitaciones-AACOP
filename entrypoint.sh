#!/bin/sh
set -e

echo "🚀 Iniciando contenedor en Producción..."

# 1. Ajustar permisos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Limpiar cachés
echo "🧹 Limpiando cachés..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# 3. Optimizar
echo "🔥 Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Migraciones
echo "📦 Ejecutando migraciones..."
php artisan migrate --force --no-interaction

# 5. 🔥 CRÍTICO: Publicar assets de Livewire para evitar error 404
echo "🎨 Publicando assets de Livewire..."
php artisan livewire:publish --assets || true

# 6. Link de Storage (con || true para que no falle si ya existe)
echo "🔗 Creando storage link..."
php artisan storage:link || true

echo "✅ Aplicación lista. Iniciando servicios..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
