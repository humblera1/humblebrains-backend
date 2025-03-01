# Stage 1: PHP Composer Build Stage
FROM php:8.2-fpm-alpine AS php-build

RUN apk --no-cache add \
    git \
    curl \
    zip \
    libxml2-dev \
    libzip-dev \
    zlib-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-dev --optimize-autoloader --classmap-authoritative

# Stage 2: Production Stage
FROM php:8.2-fpm-alpine

RUN apk --no-cache add \
    linux-headers \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
  && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
  && docker-php-ext-install \
      gd \
      pdo_mysql \
      mbstring \
      mysqli \
      exif \
      pcntl \
      bcmath \
      zip \
      opcache \
      sockets \
      intl \
      xml \
      pdo_pgsql

RUN addgroup -g 1000 -S phpuser \
    && adduser -u 1000 -G phpuser -s /bin/sh -D phpuser

WORKDIR /var/www

COPY --from=php-build /var/www /var/www

RUN mkdir -p /var/www/storage/app/public

# Fix permissions for Laravel storage and cache directories
RUN chown -R phpuser:phpuser /var/www/storage /var/www/bootstrap/cache /var/www/storage/app/public

# Switch to non-root user
USER phpuser

ENTRYPOINT ["php-fpm"]
