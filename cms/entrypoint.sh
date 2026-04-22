#!/bin/bash
set -e

# Get host user ID and group ID from environment variables, default to 1000 if not set
HOST_UID=${HOST_UID:-${LOCAL_USER_ID:-1000}}
HOST_GID=${HOST_GID:-1000}

# Create a group with the host GID if it doesn't exist
if ! getent group "$HOST_GID" > /dev/null; then
  groupadd -g "$HOST_GID" hostgroup
fi

# Add www-data user to the host group
usermod -a -G "$HOST_GID" www-data

# Fix permissions for plugins directory
if [ -d /var/www/html/site/plugins ]; then
  find /var/www/html/site/plugins -type d -exec chmod 775 {} \;
  find /var/www/html/site/plugins -type f -exec chmod 664 {} \;
  chown -R www-data:"$HOST_GID" /var/www/html/site/plugins
fi

# Detect installed PHP version (e.g. "8.3")
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
PHP_FPM_BIN="php-fpm${PHP_VERSION}"
PHP_FPM_SOCK="/run/php/php${PHP_VERSION}-fpm.sock"

if ! command -v "$PHP_FPM_BIN" >/dev/null 2>&1; then
  PHP_FPM_BIN="php-fpm"
fi

echo "Detected PHP ${PHP_VERSION}, using ${PHP_FPM_BIN}, socket ${PHP_FPM_SOCK}"

# Make sure the nginx vhost points at the right socket regardless of PHP version
NGINX_CONF="/etc/nginx/sites-available/default"
if [ -f "$NGINX_CONF" ]; then
  sed -i -E "s|unix:/run/php/php[0-9]+\.[0-9]+-fpm\.sock|unix:${PHP_FPM_SOCK}|g" "$NGINX_CONF"
fi

mkdir -p /run/php
chown -R www-data:www-data /run/php

# Start PHP-FPM in the background
echo "Starting PHP-FPM (${PHP_FPM_BIN})"
"$PHP_FPM_BIN" --nodaemonize &

# Wait briefly for the FPM socket to appear
for i in $(seq 1 20); do
  [ -S "$PHP_FPM_SOCK" ] && break
  sleep 0.25
done

if [ ! -S "$PHP_FPM_SOCK" ]; then
  echo "ERROR: PHP-FPM socket ${PHP_FPM_SOCK} did not appear" >&2
  exit 1
fi

echo "Starting Nginx"
exec nginx -g "daemon off;"
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