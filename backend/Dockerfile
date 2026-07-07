FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Render assigns $PORT at runtime; migrations run on boot so a fresh Postgres
# gets its schema without a manual step. storage:link is guarded because it
# errors if the symlink already exists (e.g. on redeploy of the same volume).
CMD php artisan migrate --force \
    && (test -L public/storage || php artisan storage:link) \
    && php artisan filament:assets \
    && php artisan config:cache \
    && php artisan serve --host 0.0.0.0 --port ${PORT:-8000}
