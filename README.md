# ForPro

A multi-service platform running Kirby CMS (API), a restaurant frontend and a website frontend, all orchestrated with Docker and Traefik.

## Architecture

```
├── cms/            Kirby CMS 5.0 (PHP 8.4, Apache)
├── restaurant/     SvelteKit 2.0 / Svelte 5 frontend (Node 24, pnpm)
├── website/        SvelteKit 2.0 / Svelte 5 frontend (Node 24, pnpm)
├── compose.dev.yml Docker Compose for local development
└── compose.prod.yml Docker Compose for production
```

Services are routed through **Traefik** reverse proxy:

| Service    | Local URL                    | Production URL              |
|------------|------------------------------|-----------------------------|
| CMS        | http://cms.localhost         | https://api.for-pro.ch      |
| Restaurant | http://restaurant.localhost  | https://foodlab.for-pro.ch  |
| Website    | http://website.localhost     | https://for-pro.ch          |
| Traefik    | http://localhost:8888        | —                           |

### CMS Plugins

| Plugin            | Notes                                            |
|-------------------|--------------------------------------------------|
| kirby-calendars   | Calendar data (critical)                         |
| kirby-foodlab     | Requires Chromium Headless + Puppeteer for PDFs  |
| kirby-forpro      | Core plugin                                      |
| kirby-menu-du-jour| Menu management                                  |
| kirby-seo         | SEO utilities                                    |

## Local Development

### Prerequisites

- Docker & Docker Compose
- Git

### 1. Clone the repository

```bash
git clone https://github.com/studio-guez/forpro.git
cd forpro/
```

### 2. Configure local hostnames

Add the following line to your `/etc/hosts` file:

```
127.0.0.1 restaurant.localhost website.localhost cms.localhost
```

### 3. Build and start all services

```bash
docker compose -f compose.dev.yml up -d --build
```



### 4. Initialize plugin data OR rsync it from Prod server

Create the required JSON files for `kirby-calendars` and `kirby-foodlab` (these directories are git-ignored):

```bash
mkdir -p cms/site/plugins/kirby-calendars/data
for f in calendars events invitations leaves schedules services; do
  echo '{}' > cms/site/plugins/kirby-calendars/data/$f.json
done

mkdir -p cms/site/plugins/kirby-foodlab/data
for f in beer bubblewine cocktail dessert hotdrink maincourse menu menu-special metadata origin redwine softdrink starter whitewine; do
  echo '[]' > cms/site/plugins/kirby-foodlab/data/$f.json
done
```

### 5. Fix permissions
```bash
docker compose -f compose.dev.yml exec cms sh -c 'chown -R www-data:www-data /var/www/html/site/sessions /var/www/html/site/accounts /var/www/html/content /var/www/html/media /var/www/html/site/plugins/*/data /var/www/html/site/cache'
```

### Access the services

- **CMS Panel**: http://cms.localhost/panel
- **Restaurant**: http://restaurant.localhost
- **Website**: http://website.localhost
- **Traefik Dashboard**: http://localhost:8888

### Development workflow

- **CMS**: Source files in `cms/` are mounted into the container. Changes to `site/plugins/`, `site/blueprints/`, `site/templates/`, `site/config/`, and `content/` are reflected immediately.
- **Restaurant / Website**: Source files are mounted with hot-reload via SvelteKit dev server.

## Sync content from PROD (local)

```bash
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:/var/www/cms/content/ ./cms/content
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:/var/www/cms/site/plugins/kirby-calendars/data/ ./cms/site/plugins/kirby-calendars/data
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:/var/www/cms/site/plugins/kirby-foodlab/data/ ./cms/site/plugins/kirby-foodlab/data
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:/var/www/cms/site/plugins/kirby-menu-du-jour/data/ ./cms/site/plugins/kirby-menu-du-jour/data
```

## Upgrading

Follow these steps whenever you bump dependency or Docker base-image versions in one of the three stacks. After upgrading, always rebuild the images (`--build`) — a plain `up` will keep running the old ones.

### Restaurant & Website (SvelteKit)

Both frontends are upgraded the same way. All `pnpm` commands run inside the running dev container — no local Node/pnpm installation is needed.

1. **Node**: bump the version in `.nvmrc` and the `node:<version>-alpine` base image in `Dockerfile.dev` and `Dockerfile.prod` (keep them in sync).
2. **Update dependencies**: run `pnpm update` inside the container to upgrade all packages to the newest versions allowed by the ranges in `package.json` and refresh `pnpm-lock.yaml`:

   ```bash
   docker compose -f compose.dev.yml exec restaurant pnpm update
   # or for the website:
   docker compose -f compose.dev.yml exec website pnpm update
   ```

   To upgrade beyond the current ranges (e.g. a new major), edit the version constraints in `package.json` first, then re-run the command above.

3. **Audit for vulnerabilities**: run `pnpm audit` inside the container and resolve any reported issues:

   ```bash
   docker compose -f compose.dev.yml exec restaurant pnpm audit
   # fix automatically where possible:
   docker compose -f compose.dev.yml exec restaurant pnpm audit --fix
   ```

4. **Build scripts**: if a new dependency needs to run install scripts, review `pnpm-workspace.yaml` (`allowBuilds`) — pnpm blocks dependency build scripts by default.
5. **Verify**: run `pnpm run check` and `pnpm run build` inside the container and fix any errors introduced by the new versions (e.g. Svelte/SvelteKit breaking changes — see the [Svelte migration guides](https://svelte.dev/docs/svelte/v5-migration-guide)):

   ```bash
   docker compose -f compose.dev.yml exec restaurant pnpm run check
   docker compose -f compose.dev.yml exec restaurant pnpm run build
   ```

6. **Rebuild the containers** to bake the updated lockfile into the image:

   ```bash
   docker compose -f compose.dev.yml up -d --build restaurant website
   ```

### CMS (Kirby)

All `composer` commands run inside the running dev container — no local PHP/Composer installation is needed.

1. **PHP / Apache**: bump the `php:<version>-apache` base image in `cms/Dockerfile.dev` and `cms/Dockerfile.prod`, and align the `php` version constraint in `cms/composer.json`.
2. **Update Kirby & Composer dependencies**: run `composer update` inside the container to upgrade all packages to the newest versions allowed by the constraints in `composer.json` and refresh `composer.lock`:

   ```bash
   docker compose -f compose.dev.yml exec cms composer update
   ```

   To upgrade beyond the current constraints (e.g. a new Kirby major), edit `cms/composer.json` first, then re-run the command above.

   > The Docker image copies both `composer.json` and `composer.lock` and runs `composer install`, so builds are fully reproducible and pinned to the exact versions in the lock file. Commit `composer.lock` after every update.

3. **Audit for vulnerabilities**: run `composer audit` inside the container and address any advisories:

   ```bash
   docker compose -f compose.dev.yml exec cms composer audit
   ```

4. **Plugins**: check every plugin in `cms/site/plugins/` for compatibility with the new Kirby major version (e.g. `kirby-seo` has a Kirby version guard in its `index.php`). Update or patch plugins as needed — plugin dependencies must be declared in `cms/composer.json` (plugins rely on the root autoloader).
5. **Verify & rebuild**:

   ```bash
   docker compose -f compose.dev.yml up -d --build cms
   ```

   Then log into the Panel at http://cms.localhost/panel and check the frontends still receive API data.
6. **Production**: rebuild with `docker compose -f compose.prod.yml up -d --build cms`, or on a non-Docker server re-run `composer install --no-dev --optimize-autoloader` and clear `site/cache/` (see the deployment section below).

## Troubleshooting

- **Permission errors on CMS**: In development, the image maps the `www-data` user to your host user via the `UID` / `GID` build args in `compose.dev.yml` (defaults: `1000` / `1000`). If your host user has a different UID/GID, adjust the args and rebuild with `docker compose -f compose.dev.yml build cms`.
- **Port conflicts**: Ensure ports `80`, `443` (prod) or `80`, `5173`, `5174`, `8000`, `8888` (dev) are not in use.

## Deployment process PROD

### 1. Sync code from local to server (run locally)

Make sure you have the latest code from `main`:

```bash
git checkout main && git pull
```

#### Website (SvelteKit)

Excludes `node_modules`, build artifacts and env files. We rebuild on the server.

```bash
rsync -avz --delete \
  --exclude='node_modules' \
  --exclude='build' \
  --exclude='.svelte-kit' \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='.DS_Store' \
  --exclude='ecosystem.config.cjs' \
  -e "ssh -i ~/.ssh/<key>" \
  ./website/ <user>@<server>:/var/www/website
```

#### Restaurant (SvelteKit)

```bash
rsync -avz --delete \
  --exclude='node_modules' \
  --exclude='build' \
  --exclude='.svelte-kit' \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='.DS_Store' \
  --exclude='ecosystem.config.cjs' \
  -e "ssh -i ~/.ssh/<key>" \
  ./restaurant/ <user>@<server>:/var/www/restaurant
```

#### CMS (Kirby)

We exclude every path that contains server-side content or is generated/installed there.

- `content/` — pages and uploads (managed via the Panel)
- `media/` — generated thumbs cache
- `site/accounts/`, `site/sessions/`, `site/cache/` — runtime state
- `site/plugins/kirby-calendars/data/`, `site/plugins/kirby-foodlab/data/`, `site/plugins/kirby-menu-du-jour/data/` — plugin data
- `kirby/`, `vendor/` — installed via `composer install` on the server
- `.env*`, `id.env` — server-specific config

```bash
rsync -avz --delete \
  --exclude='content' \
  --exclude='media' \
  --exclude='site/accounts' \
  --exclude='site/sessions' \
  --exclude='site/cache' \
  --exclude='site/plugins/kirby-calendars/data' \
  --exclude='site/plugins/kirby-foodlab/data' \
  --exclude='site/plugins/kirby-menu-du-jour/data' \
  --exclude='kirby' \
  --exclude='vendor' \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='id.env' \
  --exclude='.DS_Store' \
  --exclude='.git' \
  -e "ssh -i ~/.ssh/<key>" \
  ./cms/ <user>@<server>:/var/www/cms
```

### 2. Build & restart on the server

```bash
ssh <user>@<server> -i ~/.ssh/<key>
```

Then on the server:

Node 24 and pnpm are required for the frontends (see `.nvmrc`) — the lockfiles are `pnpm-lock.yaml` (npm is no longer used).

```bash
# Website
cd /var/www/website
pnpm install
pnpm run build
pm2 restart website

# Restaurant
cd /var/www/restaurant
pnpm install
pnpm run build
pm2 restart restaurant

# CMS
cd /var/www/cms
composer install --no-dev --optimize-autoloader
# Clear Kirby caches so new templates/blueprints are picked up
rm -rf site/cache/*
# Make sure the web user can read everything we just synced
chown -R www-data:www-data /var/www/cms
```
