#!/bin/bash
set -euo pipefail

# 0002 keeps Kirby's runtime writes group-writable so the deploy user (www-data group) can edit/rsync them without sudo.
umask 0002

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
  # setgid so files created from the host are group-owned by www-data, otherwise the Panel cannot write them.
  find "$dir" -type d -exec chmod g+ws {} +
done

# The Panel (www-data) must be able to write the license file when registering from the backend.
if [ -f /var/www/html/site/config/.license ]; then
  chown www-data:www-data /var/www/html/site/config/.license
fi

echo "Starting Apache"
exec apache2-foreground
