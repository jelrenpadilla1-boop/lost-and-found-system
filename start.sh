#!/bin/bash
sed -i "s/listen 0.0.0.0:8080/listen 0.0.0.0:${PORT:-8080}/g" /etc/nginx/sites-available/default

# Run database migrations
php artisan migrate --force

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm -D
nginx -g "daemon off;"