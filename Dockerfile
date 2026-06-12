FROM php:8.4-fpm-alpine

# Install system dependencies, PHP extensions, and Node.js/NPM
RUN apk add --no-cache alpine-sdk linux-headers bash nginx supervisor nodejs npm \
    libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev icu-dev libxml2-dev oniguruma-dev

# Install required PHP extensions for Laravel & MySQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip opcache intl bcmath mbstring xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /var/www

# Copy all project files into the container
COPY . .

# Install PHP dependencies ignoring platform checks to prevent build-time blocks
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Frontend Build Step: Install npm packages and compile assets with Vite
RUN npm install && npm run build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose the default Render port
EXPOSE 10000

# Automated Startup: Run database migrations + seeders, clear/build caches, and start the app
CMD ["sh", "-c", "php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=10000"]