FROM php:8.2-fpm-alpine

RUN apk add --no-cache bash openssl oniguruma-dev libzip-dev zip unzip icu-dev mysql-client mariadb-connector-c-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip intl

WORKDIR /var/www/html
RUN mkdir -p storage/logs storage/uploads \
    && chown -R www-data:www-data storage

EXPOSE 9000
CMD ["php-fpm"]
