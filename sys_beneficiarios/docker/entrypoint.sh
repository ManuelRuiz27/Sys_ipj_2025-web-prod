#!/usr/bin/env sh
set -e

cd /var/www/html

log() {
  printf '[entrypoint] %s\n' "$1"
}

# Ensure required directories exist
mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/app/public \
  bootstrap/cache

# Fix permissions for Laravel writable dirs
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

if [ -f composer.json ] && [ ! -f vendor/autoload.php ]; then
  log 'Installing composer dependencies...'
  composer install --no-interaction --prefer-dist
fi

# Clear caches (ignore failures if artisan not ready)
if [ -f artisan ]; then
  php artisan config:clear || true
  php artisan cache:clear || true
  php artisan view:clear || true
fi

wait_for_database() {
  if [ -z "${DB_HOST}" ] || [ -z "${DB_DATABASE}" ] || [ -z "${DB_USERNAME}" ]; then
    return 0
  fi

  until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
    log 'Waiting for database connection...'
    sleep 2
  done
}

if [ -f artisan ]; then
  wait_for_database
  php artisan storage:link || true

  MIGRATION_MARK="storage/app/.migrated"
  if [ ! -f "${MIGRATION_MARK}" ]; then
    log 'Running database migrations with seeders...'
    if php artisan migrate --force --seed; then
      touch "${MIGRATION_MARK}"
    else
      log 'Database migration + seed failed; review the container logs.'
    fi
  else
    log 'Running pending database migrations...'
    if ! php artisan migrate --force; then
      log 'Database migration failed; review the container logs.'
    fi
  fi
fi

exec "$@"
