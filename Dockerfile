# Base image with Apache + PHP 8.2
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
        libzip-dev unzip git \
    && docker-php-ext-install pdo_mysql zip \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --no-dev --optimize-autoloader

# Set Apache DocumentRoot to Laravel's public folder
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Set ServerName to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port from Railway
ENV PORT 8080
EXPOSE 8080

# Start Apache in foreground AND tail Laravel log to terminal
CMD apache2-foreground & \
    tail -f /var/www/html/storage/logs/laravel.log
