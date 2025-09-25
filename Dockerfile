# ---------- Stage 1: install Composer deps (no scripts) ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# IMPORTANT: disable scripts so "artisan" isn't required in this stage
RUN composer install \
    --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# ---------- Stage 2: PHP 8.2 + Apache ----------
FROM php:8.2-apache

# System libs & PHP extensions (SQLite + Postgres)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libpng-dev libonig-dev libxml2-dev \
    libpq-dev libsqlite3-dev sqlite3 pkg-config \
 && docker-php-ext-configure opcache --enable-opcache \
 && docker-php-ext-install -j"$(nproc)" pdo pdo_pgsql pdo_sqlite zip

# Serve the Laravel "public" directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g'  /etc/apache2/conf-available/*.conf \
 && a2enmod rewrite

WORKDIR /var/www/html

# Copy app code and vendors
COPY . .
COPY --from=vendor /app/vendor ./vendor

# SQLite file in /tmp (ephemeral)
RUN mkdir -p /tmp && touch /tmp/database.sqlite && chown -R www-data:www-data /tmp \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Runtime defaults (Koyeb injects APP_URL; you add APP_KEY in dashboard)
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV PORT=8080
EXPOSE 8080

CMD ["apache2-foreground"]
