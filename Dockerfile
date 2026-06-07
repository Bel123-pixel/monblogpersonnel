FROM php:8.2-apache

# 1. Installe les dépendances système nécessaires pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 2. Active le module Apache rewrite (indispensable pour Laravel)
RUN a2enmod rewrite

# 3. Configure le dossier public de Laravel comme racine du site
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Copie les fichiers du projet
WORKDIR /var/www/html
COPY . .

# 5. Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Donne les permissions aux dossiers de stockage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80