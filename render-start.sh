#!/usr/bin/env bash
set -e

echo "==> Running database migrations..."
php artisan migrate --force || echo "Migration skipped or database not reachable."

if [ "${DB_SEED:-false}" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force || true
fi

echo "==> Caching configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

PORT="${PORT:-10000}"
echo "==> Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port="$PORT"
