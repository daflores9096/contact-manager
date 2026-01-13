FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        zip \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
