#!/usr/bin/env bash
set -e

echo "==> Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "==> Installing Node dependencies & compiling assets..."
npm ci
npm run build

echo "==> Setting up storage..."
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

php artisan storage:link --force || true
php artisan package:discover --ansi
