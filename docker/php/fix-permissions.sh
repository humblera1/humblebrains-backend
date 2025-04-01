#!/bin/sh

# Fix permissions for Laravel storage and cache directories
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
&& chmod 775 /var/www/storage /var/www/bootstrap/cache

# Execute the passed command
exec "$@"
