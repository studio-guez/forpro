# ForPro

A multi-service platform running Kirby CMS (API), a restaurant frontend, a website frontend and a daily-menus screens app, all orchestrated with Docker and Traefik.

## Architecture

```
├── cms/            Kirby CMS 5.0 (PHP 8.4, Apache)
├── restaurant/     SvelteKit 2.0 / Svelte 5 frontend (Node 24, pnpm)
├── website/        SvelteKit 2.0 / Svelte 5 frontend (Node 24, pnpm)
├── menu/           Nuxt 4 daily-menus screens app (Node 24, pnpm)
├── compose.dev.yml Docker Compose for local development
└── compose.prod.yml Docker Compose for the deployed stack (preprod & production)
```

In **development**, services are routed through the **Traefik** reverse proxy.
In **preprod/production**, each container publishes only on a loopback port and
the host-level nginx handles domains and TLS (see [Deployment architecture](#deployment-architecture)).

| Service    | Local URL                   | Production URL             |
| ---------- | --------------------------- | -------------------------- |
| CMS        | http://cms.localhost        | https://api.for-pro.ch     |
| Restaurant | http://restaurant.localhost | https://foodlab.for-pro.ch |
| Website    | http://website.localhost    | https://for-pro.ch         |
| Menu       | http://menu.localhost       | https://menus.for-pro.ch   |
| Mailpit    | http://mailpit.localhost    | — (dev only)               |
| Traefik    | http://localhost:8888       | —                          |

### Menu app (Nuxt)

`menu/` renders the daily menus for the FoodLab and the FoodCourt screens. It is a
client-only SPA (`ssr: false`) that fetches the CMS site routes exposed by the
`kirby-menu-du-jour` plugin (`/foodlab`, `/foodcourt`, `/slider-images`).

The CMS base URL is not hardcoded: it comes from the `cmsBaseUrl` public runtime
config in `menu/nuxt.config.ts`, overridable with the `NUXT_PUBLIC_CMS_BASE_URL`
environment variable (set to `http://cms.localhost` in `compose.dev.yml`). Because the
app is a SPA, the value is baked in at build time in production — the CI build job
passes it as a Docker build arg.

The restaurant frontend links to this app through `PUBLIC_MENU_BASE_URL`
(`restaurant/.env.development` / `restaurant/.env.production`).

### CMS Plugins

| Plugin             | Notes                                                    |
| ------------------ | -------------------------------------------------------- |
| image-guard        | Downscales oversized uploads, converts CMYK JPEGs to RGB |
| kirby-foodlab      | Requires Chromium Headless + Puppeteer for PDFs          |
| kirby-menu-du-jour | Menu management                                          |
| kirby-seo          | SEO utilities                                            |

The website is served from `cms/site/` (blueprints, templates, config). The
restaurant and menu stacks live entirely in their plugins
(`kirby-foodlab`, `kirby-menu-du-jour`).

## Local Development

### Prerequisites

- Docker & Docker Compose
- Git

### 1. Clone the repository

```bash
git clone https://github.com/studio-guez/forpro.git
cd forpro/
```

### 2. Set up environment variables

```bash
cp cms/.env.example cms/.env
```

Then edit `cms/.env` and set unique random values for `KIRBY_CONTENT_SALT` and `KIRBY_COOKIE_KEY` (generate with `openssl rand -hex 32`). Set `KIRBY_VUE_COMPILER` to `true` for local development. The `KIRBY_SMTP_*` values point at the Mailpit container by default, so outgoing mail is caught locally instead of being sent (view it at http://mailpit.localhost).

### 3. Build and start all services

```bash
docker compose -f compose.dev.yml up -d --build && docker compose -f compose.dev.yml logs -f restaurant website menu
```

### 4. Initialize plugin data OR rsync it from Prod server

Create the required JSON files for `kirby-foodlab` (this directory is git-ignored):

```bash
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
- **Menu**: http://menu.localhost (FoodLab) and http://menu.localhost/foodcourt
- **Mailpit** (dev mail catcher): http://mailpit.localhost
- **Traefik Dashboard**: http://localhost:8888

### Development workflow

- **CMS**: Source files in `cms/` are mounted into the container. Changes to `site/plugins/`, `site/blueprints/`, `site/templates/`, `site/config/`, and `content/` are reflected immediately.
- **Restaurant / Website**: Source files are mounted with hot-reload via SvelteKit dev server.
- **Menu**: Source files are mounted with hot-reload via the Nuxt dev server.

## Maintenance: fix oversized / CMYK images

Oversized originals and CMYK JPEGs (a common export from Illustrator/Photoshop) can exhaust PHP's memory limit when Kirby/GD generates thumbnails. New uploads are handled automatically by the `image-guard` plugin (`cms/site/plugins/image-guard`), which downscales images whose longest edge exceeds 4000 px and converts CMYK JPEGs to RGB.

For images that are already in `content/`, run the one-off cleanup script `cms/site/plugins/image-guard/fix-large-images.php` inside the container:

```bash
# List what would change, without writing anything:
docker compose -f compose.dev.yml exec cms php site/plugins/image-guard/fix-large-images.php --dry-run

# Actually fix the files in place:
docker compose -f compose.dev.yml exec cms php site/plugins/image-guard/fix-large-images.php
```

Afterwards, clear the media cache so Kirby regenerates thumbnails from the fixed originals:

```bash
docker compose -f compose.dev.yml exec cms sh -c 'rm -rf media/pages media/site'
```

Both the plugin and the script share the same logic in `cms/site/plugins/image-guard/ImageGuard.php`, so the plugin is fully self-contained.

## Sync content from PROD (local)

On the servers, all mutable CMS state lives under `$DEPLOY_PATH/shared/cms/`
(see [Layout on each target server](#layout-on-each-target-server)):

```bash
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:<deploy_path>/shared/cms/content/ ./cms/content
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:<deploy_path>/shared/cms/site/plugins/kirby-foodlab/data/ ./cms/site/plugins/kirby-foodlab/data
rsync -avz --delete -e "ssh -i ~/.ssh/<key>" <user>@<server>:<deploy_path>/shared/cms/site/plugins/kirby-menu-du-jour/data/ ./cms/site/plugins/kirby-menu-du-jour/data
```

When rsyncing in the **other direction** (local → server), the transferred
files end up owned by the SSH user. The next deploy fixes ownership
automatically; to fix it immediately, run the chown/chmod command from step 3
of [First deploy](#first-deploy).

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
   docker compose -f compose.dev.yml exec restaurant pnpm audit --fix update
   ```

4. **Prune stale workarounds**: `overrides` and `minimumReleaseAgeExclude` in `pnpm-workspace.yaml` are stopgaps for upstream problems, and pnpm never reports them as obsolete. Worse, an override caps the range for every consumer in the tree, so a forgotten one silently blocks future majors. After each upgrade, comment them out, re-run `pnpm install` and `pnpm audit`, and delete whatever is no longer needed:

   ```bash
   docker compose -f compose.dev.yml exec restaurant pnpm install
   docker compose -f compose.dev.yml exec restaurant pnpm audit
   ```

5. **Build scripts**: if a new dependency needs to run install scripts, review `pnpm-workspace.yaml` (`allowBuilds`) — pnpm blocks dependency build scripts by default.
6. **Verify**: run `pnpm run check` and `pnpm run build` inside the container and fix any errors introduced by the new versions (e.g. Svelte/SvelteKit breaking changes — see the [Svelte migration guides](https://svelte.dev/docs/svelte/v5-migration-guide)):

   ```bash
   docker compose -f compose.dev.yml exec restaurant pnpm run check
   docker compose -f compose.dev.yml exec restaurant pnpm run build
   ```

7. **Rebuild the containers** to bake the updated lockfile into the image:

   ```bash
   docker compose -f compose.dev.yml up -d --build restaurant website
   ```

### Menu (Nuxt)

All `pnpm` commands run inside the running dev container — no local Node/pnpm installation is needed.

1. **Node**: bump the `node:<version>-alpine` base image in `Dockerfile.dev` and `Dockerfile.prod` (keep them in sync).
2. **Update dependencies**: run `pnpm update` inside the container to upgrade all packages to the newest versions allowed by the ranges in `package.json` and refresh `pnpm-lock.yaml`:

   ```bash
   docker compose -f compose.dev.yml exec menu pnpm update
   ```

   To upgrade beyond the current ranges (e.g. a new Nuxt major), edit the version constraints in `package.json` first, then re-run the command above.

3. **Audit for vulnerabilities**: run `pnpm audit` inside the container and resolve any reported issues:

   ```bash
   docker compose -f compose.dev.yml exec menu pnpm audit
   # fix automatically where possible:
   docker compose -f compose.dev.yml exec menu pnpm audit --fix update
   ```

   When `pnpm audit --fix` reports "0 vulnerabilities were fixed", no version inside the declared ranges is patched — the fix has to come from an `overrides` entry. Prefer overriding the *closest* dependency that upstream already fixed, and verify with `pnpm run build` that nothing breaks.

4. **Prune stale workarounds**: `overrides` and `minimumReleaseAgeExclude` in `pnpm-workspace.yaml` are stopgaps for upstream problems, and pnpm never reports them as obsolete. Worse, an override caps the range for every consumer in the tree, so a forgotten one silently blocks future majors. After each upgrade, comment them out, re-run `pnpm install` and `pnpm audit`, and delete whatever is no longer needed:

   ```bash
   docker compose -f compose.dev.yml exec menu pnpm install
   docker compose -f compose.dev.yml exec menu pnpm audit
   ```

   The current `glob` / `minimatch` overrides exist only because `nitropack` still pins `archiver@7` (which depends on the unmaintained `archiver-utils`). They become removable as soon as nitropack ships with `archiver@8`.

5. **Build scripts**: if a new dependency needs to run install scripts, review `pnpm-workspace.yaml` (`allowBuilds`) — pnpm blocks dependency build scripts by default.
6. **Verify**: run `pnpm run build` inside the container and fix any errors introduced by the new versions:

   ```bash
   docker compose -f compose.dev.yml exec menu pnpm run build
   ```

7. **Rebuild the container** to bake the updated lockfile into the image:

   ```bash
   docker compose -f compose.dev.yml up -d --build menu
   ```

### CMS (Kirby)

All `composer` commands run inside the running dev container — no local PHP/Composer installation is needed.

1. **PHP / Apache**: bump the `php:<version>-apache` base image in `cms/Dockerfile.dev` and `cms/Dockerfile.prod`, and align the `php` version constraint in `cms/composer.json`.
2. **Update Kirby & Composer dependencies**: run `composer update` inside the container to upgrade all packages to the newest versions allowed by the constraints in `composer.json` and refresh `composer.lock`:

   ```bash
   docker compose -f compose.dev.yml exec cms composer update
   ```

   To upgrade beyond the current constraints (e.g. a new Kirby major), edit `cms/composer.json` first, then re-run the command above. Also check each plugin in `cms/site/plugins/` for compatibility (e.g. `kirby-seo` has a Kirby version guard in its `index.php`).

   > The Docker image copies both `composer.json` and `composer.lock` and runs `composer install`, so builds are fully reproducible and pinned to the exact versions in the lock file. Commit `composer.lock` after every update.

3. **Audit for vulnerabilities**: run `composer audit` inside the container and address any advisories:

   ```bash
   docker compose -f compose.dev.yml exec cms composer audit
   ```

4. **Verify & rebuild**:

   ```bash
   docker compose -f compose.dev.yml up -d --build cms
   ```

   Then log into the Panel at http://cms.localhost/panel and check the frontends still receive API data.

5. **Production**: merge/push to `preprod` or `main` — CI rebuilds the image and deploys it (see [Deployment architecture](#deployment-architecture)).

## Troubleshooting

- **Permission errors on CMS**: In development, the image maps the `www-data` user to your host user via the `UID` / `GID` build args in `compose.dev.yml` (defaults: `1000` / `1000`). If your host user has a different UID/GID, adjust the args and rebuild with `docker compose -f compose.dev.yml build cms`.
- **Port conflicts**: Ensure ports `80`, `3000`, `5173`, `5174`, `8000`, `8888` (dev) or the loopback ports from `deploy.env` (deployed stack, defaults `8080`–`8083`) are not in use.

## Deployment architecture

This repository is **only** responsible for building and shipping the four
application container images. It is **not** responsible for TLS, virtual-host
routing, or any other reverse-proxy concern — those are handled by the nginx
installed directly on each target server, completely outside this project.

The deployed stack contains exactly four services (see `compose.prod.yml`):

- `cms`        — Kirby CMS (PHP 8.4 / Apache)
- `website`    — SvelteKit frontend (Node)
- `restaurant` — SvelteKit frontend (Node)
- `menu`       — Nuxt daily-menus SPA (Node)

Each service publishes only on `127.0.0.1:<port>` (defaults `8080`–`8083`,
configurable in `$DEPLOY_PATH/shared/deploy.env`). The host's nginx must
forward each public domain to the matching loopback port:

| Domain                     | Service    | Default loopback port |
| -------------------------- | ---------- | --------------------- |
| https://api.for-pro.ch     | cms        | `127.0.0.1:8080`      |
| https://for-pro.ch         | website    | `127.0.0.1:8081`      |
| https://foodlab.for-pro.ch | restaurant | `127.0.0.1:8082`      |
| https://menus.for-pro.ch   | menu       | `127.0.0.1:8083`      |

There is no `composer`, `node` or `pnpm` on the target servers — only Docker,
the application stack above, and the host-level nginx.

### Two environments

| Branch    | GitHub Environment | Image tags pushed               | Where it deploys     |
| --------- | ------------------ | ------------------------------- | -------------------- |
| `preprod` | `preprod`          | `preprod-sha-<sha7>`, `preprod` | preproduction server |
| `main`    | `production`       | `sha-<sha7>`, `latest`          | production server    |
| tag `v*`  | `production`       | `sha-<sha7>`, `latest`          | production server    |

Each environment uses its own GitHub Environment (`preprod` / `production`)
to store secrets. Production secrets are never visible to the preprod job and
vice versa. The deploy jobs each run on a self-hosted runner registered on
the corresponding server.

`workflow_dispatch` accepts a `target` input (`preprod` or `production`) and a
`services` input (`all` by default, or a comma-separated subset) for one-off
manual deploys:

```bash
# Trigger a manual deploy of everything to preproduction
gh workflow run ci.yml --ref preprod -f target=preprod

# Trigger a manual deploy of everything to production
gh workflow run ci.yml --ref main -f target=production

# Rebuild & redeploy only the cms container on production
gh workflow run ci.yml --ref main -f target=production -f services=cms
```

### Selective builds

On every push, the workflow detects which of the four service directories
changed and only tests, rebuilds and redeploys those containers. Untouched
services keep their currently running image (the deployed tag of each service
is recorded in `shared/current-tags/<service>.txt` on the server, with the
previous tag in `shared/last-tags/<service>.txt` for rollbacks). Changes to
the deployment plumbing itself (`compose.prod.yml`, `deploy.env.example`, the
workflow or the deploy action), tag pushes, and `workflow_dispatch` with
`services=all` rebuild everything.

Because the frontends bake their public URLs at build time, preprod images
are built separately with the `PREPROD_CMS_BASE_URL` and
`PREPROD_MENU_BASE_URL` repository **variables** (Settings → Secrets and
variables → Actions → Variables). If unset, they fall back to the production
URLs.

### Layout on each target server

```
$DEPLOY_PATH/                            # e.g. /srv/forpro (preprod and prod are separate hosts)
├── current -> releases/<ts>-<sha7>      # symlink to active release (compose file + env examples)
├── releases/<ts>-<sha7>/                # compose.prod.yml, deploy.env.example, cms.env.example
└── shared/
    ├── cms.env                          # CMS runtime env (secrets — chmod 660, never in git)
    ├── deploy.env                       # loopback ports for this server
    ├── cms/
    │   ├── content/                     # pages & uploads (Panel-editable)
    │   ├── media/                       # generated thumbs cache
    │   └── site/
    │       ├── accounts/  sessions/  cache/          # runtime state
    │       ├── config/.license                       # Kirby license (file bind mount)
    │       └── plugins/kirby-foodlab/data/           # plugin data (JSON)
    │       └── plugins/kirby-menu-du-jour/data/      # plugin data
    ├── current-tags/<service>.txt       # image tag currently running per service
    ├── last-tags/<service>.txt          # previous tag per service, for rollback
    └── backups/cms-*.tgz                # pre-deploy backups of content + plugin data
```

Everything under `shared/` lives on the **host filesystem**, outside any
container and outside any release directory. Image rebuilds and rollbacks
cannot touch it.

### One-time server setup (per environment)

Do this once on **each** target server (preproduction and production are
separate hosts). Replace `/srv/forpro` with whatever you set as `DEPLOY_PATH`
in that environment's secrets.

As root on Ubuntu 24.04:

```bash
apt update && apt install -y ca-certificates curl gnupg rsync
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
  https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo $VERSION_CODENAME) stable" \
  > /etc/apt/sources.list.d/docker.list
apt update && apt install -y docker-ce docker-ce-cli containerd.io \
                             docker-buildx-plugin docker-compose-plugin

adduser --disabled-password --gecos "" deploy
usermod -aG docker deploy
# Membership in www-data lets the deploy user edit/rsync the files under
# shared/ that the cms container chowns to www-data (they stay group-writable):
usermod -aG www-data deploy

sudo -u deploy mkdir -p /srv/forpro/{releases,shared}

# Register the GitHub Actions self-hosted runner (repeat for each environment)
# Download the runner package from: Settings → Actions → Runners → New self-hosted runner
# Follow the instructions shown there, then install as a service:
sudo ./svc.sh install deploy   # run as the deploy user
sudo ./svc.sh start
```

The runner must be registered with the labels matching the workflow:

- preprod runner: `self-hosted`, `forpro`, `preprod`, `docker`
- production runner: `self-hosted`, `forpro`, `production`, `docker`

Configure nginx on the host to proxy each domain to its loopback port (see
the table above), e.g. one `server { listen 443 ssl; … proxy_pass
http://127.0.0.1:8081; }` block per domain. TLS certificates and DNS are
managed there, not in this repo.

### First deploy

Trigger the first deploy with everything built (recommended, since no tags
are recorded on the server yet):

```bash
gh workflow run ci.yml --ref preprod -f target=preprod -f services=all
# or for production:
gh workflow run ci.yml --ref main -f target=production -f services=all
```

The pipeline bootstraps `shared/` (directories, `cms.env` from
`cms/.env.example`, `deploy.env` from `deploy.env.example`, an empty
`site/config/.license` file, empty `kirby-foodlab` data files) and starts the
stack. The CMS won't be fully operational until you fill in real values and
load real content. SSH in and finish the setup:

```bash
export DEPLOY_PATH=<deploy_path>
ssh deploy@<server>

# 1. Fill in the real CMS environment values.
#    At minimum: KIRBY_CONTENT_SALT, KIRBY_COOKIE_KEY (openssl rand -hex 32),
#    KIRBY_FRONTEND_URL and the KIRBY_SMTP_* credentials.
nano "$DEPLOY_PATH/shared/cms.env"
exit

# 2. Load the real content — run these from the machine holding the content
#    (your local clone or the old prod server), not on the target server:
rsync -avz --delete ./cms/content/ deploy@<server>:$DEPLOY_PATH/shared/cms/content
rsync -avz --delete ./cms/site/accounts/ deploy@<server>:$DEPLOY_PATH/shared/cms/site/accounts
rsync -avz --delete ./cms/site/plugins/kirby-foodlab/data/ deploy@<server>:$DEPLOY_PATH/shared/cms/site/plugins/kirby-foodlab/data
rsync -avz --delete ./cms/site/plugins/kirby-menu-du-jour/data/ deploy@<server>:$DEPLOY_PATH/shared/cms/site/plugins/kirby-menu-du-jour/data
# Kirby license — copy the existing .license from the old server (or skip and
# register the license from the Panel later; it persists in shared/ either way):
rsync -avz ./cms/site/config/.license deploy@<server>:$DEPLOY_PATH/shared/cms/site/config/.license
# Optional: rsync media/ too to avoid the thumbnail-regeneration CPU spike on
# first load — otherwise Kirby rebuilds it on demand:
rsync -avz --delete ./cms/media/ deploy@<server>:$DEPLOY_PATH/shared/cms/media

# 3. SSH back to the target server, then fix ownership and recreate CMS so
#    cms.env changes are loaded:
ssh deploy@<server>
export DEPLOY_PATH=<deploy_path>
cd "$DEPLOY_PATH/current"
export SHARED_PATH="$DEPLOY_PATH/shared"
export CMS_IMAGE_TAG=$(cat "$SHARED_PATH/current-tags/cms.txt")
docker compose --env-file "$SHARED_PATH/deploy.env" -f compose.prod.yml \
  exec --user root cms sh -c 'chown -R www-data:www-data /var/www/html/content /var/www/html/media /var/www/html/site && chmod -R g+w /var/www/html/content /var/www/html/media /var/www/html/site'
docker compose --env-file "$SHARED_PATH/deploy.env" -f compose.prod.yml up -d --force-recreate --no-deps --wait cms
```

### What happens on `git push`

1. `changes` detects which service directories were touched.
2. `check` runs the production build for each changed service
   (`composer install` for the cms, `pnpm run build` for the frontends) —
   also on pull requests, without deploying.
3. `build` builds each changed service's `Dockerfile.prod` and pushes it to
   GHCR (`ghcr.io/studio-guez/forpro/<service>`):
   - `preprod` branch → tags `preprod-sha-<sha7>` and `preprod`
   - `main` branch (or `v*` tag) → tags `sha-<sha7>` and `latest`
4. `deploy-preprod` / `deploy-production` runs on the self-hosted runner of
   the matching server:
   - a new release directory is created and `shared/` is bootstrapped
     (idempotent — every seed step is a no-op when the target exists);
   - CMS content, accounts, the license file and plugin data are backed up to
     `shared/backups/` (last 14 kept);
   - the new images are pulled; unchanged services keep their recorded tag;
   - ownership under `shared/cms` is fixed (www-data, group-writable) and the
     Kirby cache is cleared when a new cms image ships — both run as root
     inside a throwaway container;
   - the `current` symlink is flipped and `docker compose up -d
     --remove-orphans --wait` replaces only the containers whose image
     changed, then blocks until every service passes its healthcheck — an
     unhealthy container fails the deploy;
   - old releases (keep 5) and old images (keep the 5 most recent `sha-*`
     images per service for rollback) are pruned.

### Rollback

The previously deployed tag of each service is kept in
`shared/last-tags/<service>.txt`. To roll one service back:

```bash
ssh deploy@<server>
export DEPLOY_PATH=<deploy_path> SHARED_PATH=<deploy_path>/shared
cd "$DEPLOY_PATH/current"

# e.g. roll back the website
export WEBSITE_IMAGE_TAG=$(cat "$SHARED_PATH/last-tags/website.txt")
docker compose --env-file "$SHARED_PATH/deploy.env" -f compose.prod.yml up -d --no-deps --wait website
echo "$WEBSITE_IMAGE_TAG" > "$SHARED_PATH/current-tags/website.txt"
```

(Or simply re-run the workflow from the last good commit.)

### Manual deploy (when CI/CD is unavailable)

If the runner is offline you can trigger the same sequence manually after the
images have been pushed to GHCR:

```bash
ssh deploy@<server>
export DEPLOY_PATH=<deploy_path> SHARED_PATH=<deploy_path>/shared
cd "$DEPLOY_PATH/current"

# Pick the tag from the GitHub Actions "build & push" step output.
# Only set the *_IMAGE_TAG vars of the services you want to update.
export CMS_IMAGE_TAG=sha-<sha7>          # or preprod-sha-<sha7> on preprod

docker compose --env-file "$SHARED_PATH/deploy.env" -f compose.prod.yml pull cms
docker compose --env-file "$SHARED_PATH/deploy.env" -f compose.prod.yml up -d --force-recreate --no-deps --wait cms
echo "$CMS_IMAGE_TAG" > "$SHARED_PATH/current-tags/cms.txt"
```

### Required GitHub Actions secrets & variables

Secrets are scoped to **GitHub Environments** so that the preproduction
deploy job cannot read production secrets and vice versa. Configure each
environment under **Settings → Environments → `preprod`** and
**Settings → Environments → `production`** with the same key names but the
environment-appropriate values:

| Secret                 | Scope                 | Purpose                                                          |
| ---------------------- | --------------------- | ---------------------------------------------------------------- |
| `DEPLOY_PATH`          | per environment       | e.g. `/srv/forpro`                                               |
| `GHCR_PULL_TOKEN`      | per environment       | PAT with `read:packages`, used by the runner to pull from GHCR   |
| `GHCR_PULL_USER`       | per environment (opt) | GHCR username for the pull token (defaults to actor)             |
| `COMPOSE_PROJECT_NAME` | per environment (opt) | Docker Compose project name (defaults to `forpro`)               |

| Variable                | Scope                 | Purpose                                                        |
| ----------------------- | --------------------- | -------------------------------------------------------------- |
| `PREPROD_CMS_BASE_URL`  | repository (optional) | Public CMS URL baked into preprod frontend builds              |
| `PREPROD_MENU_BASE_URL` | repository (optional) | Public menu-app URL baked into preprod restaurant builds       |

All CMS secrets (`KIRBY_CONTENT_SALT`, `KIRBY_COOKIE_KEY`, SMTP credentials,
etc.) live in `$DEPLOY_PATH/shared/cms.env` on each target server — **never**
in workflow files or git. Use different salts/keys per environment.

### Seeding shared files

The deploy action bootstraps the shared directory automatically on every
deploy. Each step is a no-op when the target already exists:

| Target on host                                       | Source                                                       |
| ---------------------------------------------------- | ------------------------------------------------------------ |
| `$SHARED_PATH/cms.env`                                | `cms/.env.example` — edit with real values, recreate `cms` with its recorded tag |
| `$SHARED_PATH/deploy.env`                             | `deploy.env.example` — edit if the default ports collide      |
| `$SHARED_PATH/cms/…` state directories                | created empty                                                 |
| `$SHARED_PATH/cms/site/plugins/kirby-foodlab/data/*.json` | seeded as `[]` (overwritten by your rsync of real data)  |
