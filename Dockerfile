# ---------- Stage 1: install Composer deps (no scripts) ----------
FROM composer:2 AS vendor
WORKDIR /app
# copy only the files Composer needs
COPY composer.json composer.lock ./
# IMPORTANT: --no-scripts so it won't try to run "artisan"
RUN composer install \
    --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# ---------- Stage 2: PHP 8.2 + Apache ----------
FROM php:8.2-apache

# System libs & PHP extensions (SQLite + Postgres PDO just in case)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libpng-dev libonig-dev libxml2-dev libpq-dev sqlite3 \
 && docker-php-ext-configure opcache --enable-opcache \
 && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip

# Serve the Laravel "public" dir
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g'  /etc/apache2/conf-available/*.conf \
 && a2enmod rewrite

WORKDIR /var/www/html

# Copy the whole app, then the vendor folder from Stage 1
COPY . .
COPY --from=vendor /app/vendor ./vendor

# SQLite file in /tmp (ephemeral) – fine for the assignment
RUN mkdir -p /tmp && touch /tmp/database.sqlite && chown -R www-data:www-data /tmp \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Container defaults (Koyeb injects APP_URL and you’ll add APP_KEY in the dashboard)
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV PORT=8080
EXPOSE 8080

# Start Apache in foreground
CMD ["apache2-foreground"]
