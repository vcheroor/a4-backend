# ---------- Stage 1: install composer deps ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------- Stage 2: PHP 8.2 + Apache ----------
FROM php:8.2-apache

# System libs & PHP extensions needed by Laravel and SQLite
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip

# Make Apache serve /public and enable .htaccess rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
 && a2enmod rewrite

WORKDIR /var/www/html

# Copy app code and vendor from the builder stage
COPY . .
COPY --from=vendor /app/vendor ./vendor

# Permissions for storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# --- Option A: SQLite in /tmp (Render ephemeral FS) ---
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/tmp/database.sqlite
RUN mkdir -p /tmp && touch /tmp/database.sqlite && chown -R www-data:www-data /tmp

# Production defaults; Render will inject APP_URL and APP_KEY
ENV APP_ENV=production
ENV APP_DEBUG=false

# Render expects your app to listen on $PORT. Run Apache on that port.
ENV PORT=8080
EXPOSE 8080
RUN sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Start Apache in the foreground
CMD ["apache2-foreground"]
