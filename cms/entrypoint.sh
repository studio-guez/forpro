#!/bin/bash

# Get host user ID and group ID from environment variables, default to 1000 if not set
HOST_UID=${HOST_UID:-1000}
HOST_GID=${HOST_GID:-1000}

# Create a group with the host GID if it doesn't exist
if ! getent group $HOST_GID > /dev/null; then
  groupadd -g $HOST_GID hostgroup
fi

# Add www-data user to the host group
usermod -a -G $HOST_GID www-data

# Fix permissions for plugins directory
find /var/www/html/site/plugins -type d -exec chmod 775 {} \;
find /var/www/html/site/plugins -type f -exec chmod 664 {} \;
chown -R www-data:$HOST_GID /var/www/html/site/plugins

# Find the correct PHP-FPM service name or binary
PHP_FPM_SERVICE=$(ls /etc/init.d/ | grep php.*fpm | head -n 1)

if [ -n "$PHP_FPM_SERVICE" ]; then
  echo "Starting PHP-FPM service: $PHP_FPM_SERVICE"
  service $PHP_FPM_SERVICE start
else
  # Find PHP version and start PHP-FPM directly
  PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.\".\" .PHP_MINOR_VERSION;")
  echo "Starting PHP-FPM version $PHP_VERSION directly"
  if [ -x "$(command -v php-fpm$PHP_VERSION)" ]; then
    php-fpm$PHP_VERSION --nodaemonize &
  else
    php-fpm --nodaemonize &
  fi
fi

echo "Starting Nginx"
nginx -g "daemon off;"