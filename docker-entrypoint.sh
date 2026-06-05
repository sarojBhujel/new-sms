#!/bin/sh
set -e

# Ensure correct permissions for storage and cache directories
if [ -d /var/www/html ]; then
  chown -R www-data:www-data /var/www/html || true
  if [ -d /var/www/html/storage ]; then
    chmod -R 775 /var/www/html/storage || true
  fi
  if [ -d /var/www/html/bootstrap/cache ]; then
    chmod -R 775 /var/www/html/bootstrap/cache || true
  fi
fi

# Run composer install if vendor is missing
if [ ! -f /var/www/html/vendor/autoload.php ]; then
  if command -v composer >/dev/null 2>&1; then
    cd /var/www/html || exit 1
    composer install --no-interaction --prefer-dist --optimize-autoloader || true
  fi
fi

# Execute the container command
exec "$@"
