#!/usr/bin/env bash
set -e

echo "=== Installation des dépendances PHP ==="
composer install --no-dev --optimize-autoloader --no-interaction

echo "=== Génération de la clé ==="
php artisan key:generate --force

echo "=== Cache de configuration ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Migrations ==="
php artisan migrate --force

echo "=== Lien storage ==="
php artisan storage:link || true

echo "=== Compte admin ==="
php artisan db:seed --class=AdminSeeder --force || true

echo "=== Build terminé ==="
