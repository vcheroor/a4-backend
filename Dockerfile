# ---------- Stage 1: Composer (build vendor) ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
COPY . .

# ---------- Stage 2: PHP 8.2 + Apache ----------
FROM php:8.2-apache

# System libs & PHP extensions (pgsql + sqlite)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libpng-dev libonig-dev libxml2-dev \
    libpq-dev sqlite3 \
 && docker-php-ext-configure opcache --enable-opcache \
 && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip

# Apache docroot to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf \
 && a2enmod rewrite

WORKDIR /var/www/html

# Copy app & vendor from builder
COPY . .
COPY --from=vendor /app/vendor ./vendor

# Permissions for caches
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Laravel will listen on this, and Koyeb will route to it
ENV PORT=8080
EXPOSE 8080

# Start PHP’s built-in server through Apache foreground (Apache image already handles it)
CMD ["apache2-foreground"]
