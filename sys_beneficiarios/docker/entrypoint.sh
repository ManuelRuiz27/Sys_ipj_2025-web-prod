#!/usr/bin/env sh
set -e

cd /var/www/html

# Ensure required directories exist
mkdir -p storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         storage/app/public \
         bootstrap/cache

# Fix permissions for Laravel writable dirs
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

# Clear caches (ignore failures if artisan not ready)
if [ -f artisan ]; then
  php artisan config:clear || true
  php artisan cache:clear || true
  php artisan view:clear || true
fi

exec "$@"

