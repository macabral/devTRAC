FROM php:8.2-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1 

RUN apk update && apk add \
    icu-dev \
    oniguruma-dev \
    tzdata \
    curl \
    nano \
    bash \
    git \
    zlib-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip 

RUN docker-php-ext-install intl \
&& docker-php-ext-install pcntl \
&& docker-php-ext-install pdo_mysql \
&& docker-php-ext-install mbstring \
&& docker-php-ext-enable opcache \
&& docker-php-ext-install bcmath \
&& docker-php-ext-install gd \
&& docker-php-ext-install zip \
&& docker-php-ext-install fileinfo

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
# RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app

VOLUME ./src /var/www/app

COPY ./src .

RUN composer install --no-dev --no-scripts

USER root

RUN chmod 777 -R /var/www/app

RUN chmod 777 -R /var/www/app/storage

RUN chmod 777 -R /var/www/app/public


