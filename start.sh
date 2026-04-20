#!/bin/bash
# Replace the hardcoded 8080 with Render's PORT environment variable
sed -i "s/listen 0.0.0.0:8080/listen 0.0.0.0:${PORT:-8080}/g" /etc/nginx/sites-available/default

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground
nginx -g "daemon off;"