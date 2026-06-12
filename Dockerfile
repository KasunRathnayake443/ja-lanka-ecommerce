FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache alpine-sdk linux-headers bash nginx supervisor \
    libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev icu-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip opcache intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies (ignoring dev tools for production)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx and Supervisor configuration configurations 
# (We will configure the container to run Nginx and PHP-FPM together)
EXPOSE 10000

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=10000"]