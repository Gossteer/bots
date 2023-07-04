ARG IMAGE_NAME=php
ARG VERSION=8.2-fpm

FROM ${IMAGE_NAME}:${VERSION} as base

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -y libicu-dev libzip-dev libpq-dev librabbitmq-dev zip curl unzip

RUN pecl install apcu redis amqp

RUN docker-php-ext-configure pdo_pgsql --with-pdo-pgsql && \
       docker-php-ext-configure intl --enable-intl

RUN docker-php-ext-install -j$(nproc) \
        zip \
        pdo_pgsql \
        intl \
        pcntl

RUN docker-php-ext-enable apcu redis amqp

WORKDIR /var/www/app

EXPOSE 9000

CMD ["php-fpm", "-F"]

ADD ./.docker/app/php.ini  /usr/local/etc/php/conf.d/custom.ini
ADD ./.docker/app/php.local.ini /usr/local/etc/php/conf.d/custom-2.ini

# Change www-data user to local id
RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data

USER 1000