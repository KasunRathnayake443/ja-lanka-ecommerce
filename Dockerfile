FROM php:8.2-fpm-alpine

# Install system dependencies and Nginx
RUN apk add --no-cache nginx wget nodejs npm supervisor

# Install PHP extensions for MySQL/PostgreSQL compatibility
RUN docker-php-ext-install pdo pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies safely for production
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Setup correct file permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx configuration overrides (Created in next step)
COPY ./deploy/nginx.conf /etc/nginx/nginx.conf
COPY ./deploy/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]