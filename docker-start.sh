#!/bin/bash
set -e

echo "=== PORT reçu : ${PORT} ==="

# Création du .env
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

# Configuration Apache port dynamique
APACHE_PORT="${PORT:-8080}"
echo "=== Configuration Apache sur port : ${APACHE_PORT} ==="

# Réécrire ports.conf
echo "Listen ${APACHE_PORT}" > /etc/apache2/ports.conf

# Réécrire le VirtualHost complètement
cat > /etc/apache2/sites-available/000-default.conf << VHOST
<VirtualHost *:${APACHE_PORT}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
VHOST

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

echo "=== Démarrage Apache sur port ${APACHE_PORT} ==="
exec apache2-foreground