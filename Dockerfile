# Dockerfile

# Use PHP 8 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y libzip-dev unzip git \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --no-dev --optimize-autoloader

# Expose port from Cloud Run
ENV PORT 8080
EXPOSE 8080

# Start Laravel server on $PORT
CMD php artisan serve --host=0.0.0.0 --port=$PORT
