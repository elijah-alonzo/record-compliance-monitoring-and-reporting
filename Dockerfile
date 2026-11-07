# syntax=docker/dockerfile:1
#
# Production image: PHP-FPM + NGINX (serversideup/php), listening on port 8080.
# The simplest way to run it is docker-compose.yml, next to this file:
#
#   echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env   # once; keep it safe
#   # then add APP_URL, ADMIN_EMAIL, ADMIN_PASSWORD and the MAIL_* settings to .env
#   docker compose up -d --build
#
# On every start it prepares the storage volume, runs migrations, links public
# storage, caches config, routes and views, then seeds the panel roles and the
# first admin if they are missing. Everything else (departments, faculty,
# teaching loads, deadlines, other accounts) is set up in /registrar.
# Data: SQLite in the storage volume by default; set DB_CONNECTION (mysql,
# pgsql) and the DB_* variables to use a database server instead.
# The same image runs the daily email reminders as the `scheduler` service.

ARG PHP_IMAGE=serversideup/php:8.4-fpm-nginx-v4.5.3
ARG NODE_IMAGE=node:24.22.0-bookworm-slim

# ---- PHP with the extensions the app needs on top of the image's defaults ----
FROM ${PHP_IMAGE} AS base
USER root
RUN install-php-extensions intl
USER www-data
WORKDIR /var/www/html

# ---- Composer packages, without dev tools ----
FROM base AS vendor
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

# ---- Front-end build ----
# The registrar panel's theme imports Filament's CSS from vendor/, so this
# stage has PHP, the Composer packages and Node. Only `public/build` leaves it.
FROM ${NODE_IMAGE} AS node
FROM base AS assets
USER root
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
COPY package.json package-lock.json ./
RUN npm ci
COPY --from=vendor /var/www/html/vendor ./vendor
COPY . .
# Booting artisan here needs no real database or cache.
RUN DB_CONNECTION=sqlite DB_DATABASE=:memory: CACHE_STORE=array \
    sh -c 'composer dump-autoload --no-dev && npm run build'

# ---- The app ----
FROM base AS app
ENV AUTORUN_ENABLED=true \
    PHP_OPCACHE_ENABLE=1 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    QUEUE_CONNECTION=sync \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/html/storage/database/database.sqlite
COPY --chown=www-data:www-data --from=vendor /var/www/html/vendor ./vendor
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /var/www/html/public/build ./public/build

# Start-up scripts, run in name order around the image's own
# 50-laravel-automations (migrate, storage:link, caches).

# Before: a new storage volume is empty, so create Laravel's folders and the SQLite file.
COPY --chmod=755 <<'EOF' /etc/entrypoint.d/10-cmrs-storage.sh
#!/bin/sh
if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is not set. Generate one: echo base64:$(openssl rand -base64 32)" >&2
    exit 1
fi
cd /var/www/html || exit 1
mkdir -p storage/app/private storage/app/public storage/framework/cache/data \
    storage/framework/sessions storage/framework/views storage/logs
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ -n "${DB_DATABASE:-}" ] && [ "$DB_DATABASE" != ":memory:" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    [ -f "$DB_DATABASE" ] || touch "$DB_DATABASE"
fi
EOF

# After: seed the panel roles and the first admin (ADMIN_EMAIL, ADMIN_PASSWORD).
# It only adds what is missing. Skipped where automations are off (the scheduler).
COPY --chmod=755 <<'EOF' /etc/entrypoint.d/60-cmrs-seed.sh
#!/bin/sh
[ "${AUTORUN_ENABLED:-false}" = "true" ] || exit 0
cd /var/www/html && php artisan db:seed --force --no-interaction
EOF

# Also runs package discovery and publishes Filament's assets into public/.
RUN DB_DATABASE=:memory: CACHE_STORE=array \
    composer dump-autoload --no-dev --optimize --classmap-authoritative
