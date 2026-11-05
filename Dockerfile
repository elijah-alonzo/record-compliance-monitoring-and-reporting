# Untested: written for show, no Docker available locally.
FROM php:8.4-cli

RUN apt-get update && apt-get install -y git unzip curl libicu-dev libsqlite3-dev libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install intl pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN cp -n .env.example .env \
    && composer install --no-interaction --prefer-dist \
    && npm ci && npm run build \
    && touch database/database.sqlite \
    && php artisan key:generate

EXPOSE 8000
CMD ["sh", "-c", "php artisan migrate --force && php artisan shield:generate --panel=registrar --all --option=permissions --no-interaction && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000"]
