FROM php:8.1-fpm-buster

ARG user
ARG uid

# Install some helpful packages
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

#clean the repostory cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#install laravel required extentions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# install and enable xdebug
RUN pecl install xdebug-3.1.3 \
    && pecl install redis-5.3.7 \
	&& docker-php-ext-enable xdebug redis

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# create php.ini file for development
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Set working directory
WORKDIR /var/www/html

 
