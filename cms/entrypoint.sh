#!/bin/bash
set -euo pipefail

# Directories Kirby writes to at runtime. They are bind-mounted from the host, so
# make sure they exist and are owned by the web user on every start.
for dir in \
  /var/www/html/content \
  /var/www/html/media \
  /var/www/html/site/accounts \
  /var/www/html/site/cache \
  /var/www/html/site/sessions \
  /var/www/html/site/plugins/kirby-foodlab/data \
  /var/www/html/site/plugins/kirby-menu-du-jour/data
do
  mkdir -p "$dir"
  chown -R www-data:www-data "$dir"
done

echo "Starting Apache"
exec apache2-foreground
