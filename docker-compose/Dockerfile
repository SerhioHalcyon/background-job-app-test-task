FROM php:8.3.8-fpm

ARG uid=1000
ARG user=serhio

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip pdo_mysql exif pcntl bcmath gd

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install Composer
COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

USER $user
