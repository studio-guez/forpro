# ForPro

A multi-service platform running Kirby CMS (API), a restaurant frontend and a website frontend, all orchestrated with Docker and Traefik.

## Architecture

```
├── cms/            Kirby CMS 4.0 (PHP 8.1, Nginx)
├── restaurant/     SvelteKit 2.0 frontend
├── website/        SvelteKit 2.0 frontend
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

Where `<service>` is `cms`, `restaurant`, or `website`.

## Troubleshooting

- **Permission errors on CMS**: The entrypoint script aligns container permissions with the host UID/GID. If issues persist, check `HOST_UID` / `HOST_GID` environment variables in `compose.dev.yml`.
- **Port conflicts**: Ensure ports `80`, `443` (prod) or `80`, `5173`, `5174`, `8000`, `8888` (dev) are not in use.

## Deployment process PROD

### 1. Sync code from local to server (run locally)

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

```bash
# Website
cd /var/www/website
npm install
npm run build
pm2 restart website

# Restaurant
cd /var/www/restaurant
npm install
npm run build
pm2 restart restaurant

# CMS
cd /var/www/cms
composer install --no-dev --optimize-autoloader
# Clear Kirby caches so new templates/blueprints are picked up
rm -rf site/cache/*
# Make sure the web user can read everything we just synced
chown -R www-data:www-data /var/www/cms
```
