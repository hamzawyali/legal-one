# Use an official PHP runtime as a parent image
FROM php:8.2-fpm
# Set working directory
WORKDIR /var/www/html
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    vim
# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip exif pcntl
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Set environment variable to allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1
# Copy application code
COPY . .
# Expose port 9000 and start php-fpm server
EXPOSE 9000
RUN useradd -u 1000 -G www-data,root -d /home/web -m web
RUN mkdir -p /home/web/.composer && \
    chown -R web:web /home/web
USER web
CMD ["php-fpm"]