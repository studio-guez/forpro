#!/bin/bash
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

chown -R www-data:www-data /var/www/html/site/plugins/kirby-foodlab/data
chown -R www-data:www-data /var/www/html/site/plugins
chown -R www-data:www-data /var/www/html/site/config
chown -R www-data:www-data /var/www/html/media
chown -R www-data:www-data /var/www/html/site/cache
chown -R www-data:www-data /var/www/html/site/sessions
chown -R www-data:www-data /var/www/html/site/accounts

# Start Nginx in foreground
echo "Starting Nginx"
nginx -g "daemon off;"