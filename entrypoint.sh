#!/bin/sh
set -e

echo "🚀 Iniciando contenedor en Producción..."

# Aseguramos que no exista un .env que cause conflictos
if [ -f .env ]; then
    echo "🗑️ Eliminando archivo .env residual..."
    rm .env
fi

echo "📦 Ejecutando migraciones..."
# Force migration corre las migraciones contra la DB configurada en Render
php artisan migrate --force

echo "🔥 Limpiando cachés..."
# IMPORTANTE: Usamos 'clear' en lugar de 'cache' para asegurar que lea las variables de entorno de Render
php artisan config:clear
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "✅ Configuración lista. Iniciando servidor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
