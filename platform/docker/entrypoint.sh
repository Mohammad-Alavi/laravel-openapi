#!/bin/sh
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Cache configuration, routes, and views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor (manages php-fpm, nginx, queue worker)
exec /usr/bin/supervisord -c /etc/supervisord.conf
