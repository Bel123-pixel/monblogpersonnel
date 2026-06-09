#!/bin/bash
set -e

echo "=== Génération clé ==="
php artisan key:generate --force

echo "=== Config cache ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Migrations ==="
php artisan migrate --force

echo "=== Storage link ==="
php artisan storage:link || true

echo "=== Admin seeder ==="
php artisan db:seed --class=AdminSeeder --force || true

echo "=== Démarrage Apache ==="
apache2-foreground