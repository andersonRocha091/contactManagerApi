# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Xdebug configuration
RUN { \
    echo "xdebug.mode=debug"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.client_port=9003"; \
    echo "xdebug.log=/tmp/xdebug.log"; \
    echo "xdebug.discover_client_host=false"; \
} >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www