#!/bin/bash
set -e

echo "=== Création du .env ==="
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER="${SESSION_DRIVER:-cookie}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
CACHE_STORE="${CACHE_STORE:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"

LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
LOG_LEVEL="${LOG_LEVEL:-error}"
EOF

echo "=== Config cache ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "=== Migrations ==="
php artisan migrate:fresh --force --seed || php artisan migrate --force

echo "=== Storage link ==="
php artisan storage:link || true

echo "=== Admin seeder ==="
php artisan db:seed --class=AdminSeeder --force || true

echo "=== Démarrage Apache ==="
apache2-foreground