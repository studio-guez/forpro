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


## Production Deployment

### 1. Build and start all services

```bash
docker compose -f compose.prod.yml up -d --build
```

This will:
- Build the CMS container (Nginx + PHP-FPM), including `composer install --no-dev`
- Build both SvelteKit apps as optimized Node.js production bundles
- Start Traefik with HTTPS entrypoints on ports 80 and 443

> **Note**: Run `composer install` locally and push before deploying so that `composer.json` and `composer.lock` are up-to-date in the image build.

### 2. Verify services

```bash
docker compose -f compose.prod.yml ps
```

All services should show as `running`.

### Production URLs

- **CMS**: https://api.for-pro.ch
- **Restaurant**: https://foodlab.for-pro.ch
- **Website**: https://for-pro.ch

### Rebuild and restart a single service

```bash
docker compose -f compose.prod.yml up -d --build <service>
```

Where `<service>` is `cms`, `restaurant`, or `website`.

## Troubleshooting

- **Permission errors on CMS**: The entrypoint script aligns container permissions with the host UID/GID. If issues persist, check `HOST_UID` / `HOST_GID` environment variables in `compose.dev.yml`.
- **Port conflicts**: Ensure ports `80`, `443` (prod) or `80`, `5173`, `5174`, `8000`, `8888` (dev) are not in use.
