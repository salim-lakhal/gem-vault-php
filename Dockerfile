FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    icu-dev \
    libpq-dev \
    && docker-php-ext-install \
    intl \
    pdo_pgsql \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

FROM base AS deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --no-progress

FROM base AS app
COPY --from=deps /app/vendor vendor/
COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative \
    && php bin/console cache:warmup --env=prod

RUN chown -R www-data:www-data var/

EXPOSE 9000
CMD ["php-fpm"]
