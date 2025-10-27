# -------------------------------------------------------------------
# Builder Stage: Установка Composer
# -------------------------------------------------------------------
FROM composer:latest AS composer

# -------------------------------------------------------------------
# Runtime Stage: PHP-FPM с Alpine
# -------------------------------------------------------------------
FROM php:8.3-fpm-alpine

# Устанавливаем необходимые зависимости для работы PHP и расширений.
RUN apk add --no-cache \
    git \
    $PHPIZE_DEPS \
    libxml2-dev \
    icu-dev \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    mariadb-client \
    mariadb-connector-c-dev


# Устанавливаем расширения PHP
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) opcache

# Очистка кэша
RUN rm -rf /tmp/*

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем Composer из Builder Stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Назначаем права для пользователя www-data
RUN chown -R www-data:www-data /app && chmod -R 775 /app

RUN mkdir web/assets

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
