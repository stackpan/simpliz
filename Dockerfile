FROM php:8.1-fpm-alpine

RUN set -ex && apk --no-cache add postgresql-dev

RUN docker-php-ext-install pdo_mysql pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN php artisan config:cache
RUN php artisan route:cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
