#!/bin/bash
cd /var/app/current

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force