FROM php:8.2-fpm-alpine

RUN apk --no-cache add \
    git \
    curl \
    zip \
    libxml2-dev \
    libzip-dev \
    zlib-dev \
    linux-headers \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    autoconf \
    make \
    gcc \
    g++

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install \
          pdo_mysql \
          mbstring \
          mysqli \
          exif \
          pcntl \
          bcmath \
          gd \
          zip \
          opcache \
          sockets \
          intl \
          xml \
          pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# переменная окружения для Xdebug
ENV PHP_IDE_CONFIG 'serverName=app-dev'

COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN touch /tmp/xdebug.log \
    && chown www-data:www-data /tmp/xdebug.log \
    && chmod 666 /tmp/xdebug.log

RUN addgroup -g 1000 -S phpuser \
    && adduser -u 1000 -G phpuser -s /bin/sh -D phpuser

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-interaction

ENTRYPOINT ["php-fpm"]
