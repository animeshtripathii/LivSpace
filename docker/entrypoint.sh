#!/bin/sh
set -e

echo "==> Yadav Interior Application Starting..."

# Ensure a valid APP_KEY is present
if [ -z "$APP_KEY" ] || [ ${#APP_KEY} -lt 32 ]; then
    echo "==> APP_KEY missing or invalid length. Generating valid application key..."
    php artisan key:generate --force || true
fi

# Ensure storage directories and permissions exist
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# Ensure database directory exists if sqlite is used
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p database
    if [ ! -f "database/database.sqlite" ]; then
        echo "==> Creating SQLite database file..."
        touch database/database.sqlite
    fi
fi

# Ensure storage symlink exists
echo "==> Linking storage directory..."
php artisan storage:link --force || true

# Run database migrations if AUTO_MIGRATE is true or by default
if [ "${AUTO_MIGRATE:-true}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || echo "Migration encountered an issue or database is not yet ready."
    
    # Automatically seed initial categories, designers, and projects
    echo "==> Ensuring initial database records and sample projects exist..."
    php artisan db:seed --force || echo "Database seeding step completed."
fi

# Optimize Laravel cache for production
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Caching configuration, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

PORT="${PORT:-10000}"
echo "==> Starting web server on 0.0.0.0:${PORT}..."

exec php artisan serve --host=0.0.0.0 --port="$PORT"
