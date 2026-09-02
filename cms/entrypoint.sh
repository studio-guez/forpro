#!/bin/bash
set -euo pipefail

# Kirby creates content files and directories with bare `mkdir()` /
# `file_put_contents()`, i.e. mode 0777/0666 masked by the process umask. Apache
# inherits umask 022 by default, so every runtime write (page.txt, _changes/,
# uploaded files) lands as 755/644 and is NOT group-writable — the deploy user on
# the host, a member of the www-data group, then cannot edit or rsync it without
# sudo. 0002 keeps the group write bit so both sides can work on the same tree.
umask 0002

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
  # setgid so anything created inside from the host (git pull, rsync, an editor)
  # is group-owned by www-data instead of the host user's own group — otherwise
  # the Panel cannot write files it does not own. Directories only: the tree is
  # small enough for this to be free, unlike a recursive pass over the files.
  find "$dir" -type d -exec chmod g+ws {} +
done

# The Kirby license is a file bind mount (see compose.prod.yml); make sure the
# Panel (www-data) can write it when the license is registered from the backend.
if [ -f /var/www/html/site/config/.license ]; then
  chown www-data:www-data /var/www/html/site/config/.license
fi

echo "Starting Apache"
exec apache2-foreground
